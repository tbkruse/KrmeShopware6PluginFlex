// ***********************************************
// This example commands.js shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************
//
//
// -- This is a parent command --
// Cypress.Commands.add("login", (email, password) => { ... })
//
//
// -- This is a child command --
// Cypress.Commands.add("drag", { prevSubject: 'element'}, (subject, options) => { ... })
//
//
// -- This is a dual command --
// Cypress.Commands.add("dismiss", { prevSubject: 'optional'}, (subject, options) => { ... })
//
//
// -- This is will overwrite an existing command --
// Cypress.Commands.overwrite("visit", (originalFn, url, options) => { ... })

import '@percy/cypress';

const uuid = require('uuid/v4');
const AdminApiService = require('@shopware-ag/e2e-testsuite-platform/cypress/support/service/administration/admin-api.service.js');

/**
 * Switches administration UI locale to EN_GB
 * @memberOf Cypress.Chainable#
 * @name setLocaleToEnGb
 * @function
 */
Cypress.Commands.add('setLocaleToEnGb', () => {
    return cy.window().then((win) => {
        win.localStorage.setItem('sw-admin-locale', Cypress.env('locale'));
    });
});

/**
 * Cleans up any previous state by restoring database and clearing caches
 * @memberOf Cypress.Chainable#
 * @name openInitialPage
 * @function
 */
Cypress.Commands.add('openInitialPage', (url) => {
    // Request we want to wait for later

    cy.intercept('/api/_info/me').as('meCall');


    cy.visit(url);
    cy.wait('@meCall').then(() => {
        cy.get('.sw-desktop').should('be.visible');
    });
});

/**
 * Authenticate towards the Shopware API
 * @memberOf Cypress.Chainable#
 * @name authenticate
 * @function
 */
Cypress.Commands.add('authenticate', () => {
    return cy.request(
        'POST',
        '/api/oauth/token',
        {
            grant_type: Cypress.env('grant') ? Cypress.env('grant') : 'password',
            client_id: Cypress.env('client_id') ? Cypress.env('client_id') : 'administration',
            scopes: Cypress.env('scope') ? Cypress.env('scope') : 'write',
            username: Cypress.env('username') ? Cypress.env('user') : 'admin',
            password: Cypress.env('password') ? Cypress.env('pass') : 'shopware'
        }
    ).then((responseData) => {
        return {
            access: responseData.body.access_token,
            refresh: responseData.body.refresh_token,
            expiry: Math.round(+new Date() / 1000) + responseData.body.expires_in
        };
    });
});

/**
 * Click context menu in order to cause a desired action
 * @memberOf Cypress.Chainable#
 * @name clickContextMenuItem
 * @function
 * @param {String} menuButtonSelector - The message to look for
 * @param {String} menuOpenSelector - The message to look for
 * @param {Object} [scope=null] - Options concerning the notification
 */
Cypress.Commands.add('clickContextMenuItem', (menuButtonSelector, menuOpenSelector, scope = null) => {
    const contextMenuCssSelector = '.sw-context-menu';
    const activeContextButtonCssSelector = '.is--active';

    if (scope != null) {
        cy.get(scope).should('be.visible');
        cy.get(`${scope} ${menuOpenSelector}`).click({force: true});

        if (scope.includes('row')) {
            cy.get(`${menuOpenSelector}${activeContextButtonCssSelector}`).should('be.visible');
        }
    } else {
        cy.get(menuOpenSelector).should('be.visible').click({force: true});
    }

    cy.get(contextMenuCssSelector).should('be.visible');
    cy.get(menuButtonSelector).click();
    cy.get(contextMenuCssSelector).should('not.exist');
});

Cypress.Commands.add('installPlugin', (pluginName) => {

    return cy.authenticate().then((result) => {
        const requestConfig = {
            headers: {
                Accept: 'application/vnd.api+json',
                Authorization: `Bearer ${result.access}`,
                'Content-Type': 'application/json'
            },
            method: 'POST',
            url: `/api/_action/extension/install/plugin/${pluginName}`,
            qs: {
                response: true,
                pluginName: pluginName,
            },
            body: {}
        };

        return cy.request(requestConfig);
    });
});

Cypress.Commands.add('activatePlugin', (pluginName) => {

    return cy.authenticate().then((result) => {
        const requestConfig = {
            headers: {
                Accept: 'application/vnd.api+json',
                Authorization: `Bearer ${result.access}`,
                'Content-Type': 'application/json'
            },
            method: 'PUT',
            url: `/api/_action/extension/activate/plugin/${pluginName}`,
            qs: {
                response: true,
                pluginName: pluginName,
            },
            body: {}
        };

        return cy.request(requestConfig);
    });
});

Cypress.Commands.add('changeTheme', (themeName, salesChannel = 'Storefront') => {

    let themeId, salesChannelId;

    return cy.searchViaAdminApi({
        endpoint: 'sales-channel',
        data: {
            field: 'name',
            value: salesChannel
        }
    }).then((salesChannelObject) => {
        salesChannelId = salesChannelObject.id;
        cy.searchViaAdminApi({
            data: {
                field: 'technicalName',
                value: themeName
            },
            endpoint: 'theme'
        })
    }).then((theme) => {
        themeId = theme.id;
        cy.authenticate();
    }).then((auth) => {
        return cy.request({
            headers: {
                Accept: 'application/vnd.api+json',
                Authorization: `Bearer ${auth.access}`,
                'Content-Type': 'application/json'
            },
            method: 'POST',
            url: 'api/_action/theme/' + themeId + '/assign/' + salesChannelId,
            qs: {
                response: true
            },
            body: {}
        });
    });
});


Cypress.Commands.add('resetTheme', (themeName) => {

    let themeId, salesChannelId, accessToken;
    const pluginName = themeName;

    cy.authenticate().then((auth) => {
        accessToken = auth.access;
        cy.searchViaAdminApi({
            endpoint: 'sales-channel',
            data: {
                field: 'name',
                value: 'Raiffeisen-Markt'
            }
        });
    }).then((salesChannelObject) => {
        salesChannelId = salesChannelObject.id;
        const requestConfig = {
            headers: {
                Accept: 'application/vnd.api+json',
                Authorization: `Bearer ${accessToken}`,
                'Content-Type': 'application/json'
            },
            method: 'POST',
            url: `/api/_action/extension/install/plugin/${pluginName}`,
            qs: {
                response: true,
                pluginName: pluginName,
            },
            body: {}
        };

        return cy.request(requestConfig);
    }).then(() => {
        const requestConfig = {
            headers: {
                Accept: 'application/vnd.api+json',
                Authorization: `Bearer ${accessToken}`,
                'Content-Type': 'application/json'
            },
            method: 'PUT',
            url: `/api/_action/extension/activate/plugin/${pluginName}`,
            qs: {
                response: true,
                pluginName: pluginName,
            },
            body: {}
        };

        return cy.request(requestConfig);
    }).then(() => {
        cy.searchViaAdminApi({
            data: {
                field: 'technicalName',
                value: 'AgravisMarkets'
            },
            endpoint: 'theme'
        })
    }).then((theme) => {
        themeId = theme.id;
        cy.request({
            headers: {
                Accept: 'application/vnd.api+json',
                Authorization: `Bearer ${accessToken}`,
                'Content-Type': 'application/json'
            },
            method: 'POST',
            url: 'api/_action/theme/' + themeId + '/assign/' + salesChannelId,
            qs: {
                response: true
            },
            body: {}
        });
    });
});

Cypress.Commands.add('addProduct', (productName) => {

    let taxId, currencyId;

    cy.createDefaultFixture('tax').then(() => {
        cy.searchViaAdminApi({
            data: {
                field: 'name',
                value: 'Standard rate'
            },
            endpoint: 'tax'
        })
    }).then(tax => {
        taxId = tax.id;

        cy.searchViaAdminApi({
            data: {
                field: 'name',
                value: 'Euro'
            },
            endpoint: 'currency'
        })
    }).then(currency => {
        currencyId = currency.id;

        cy.authenticate();
    }).then(auth => {
        let products = [];
        products.push(
            {
                name: productName,
                stock: 10,
                productNumber: uuid().replace(/-/g, ''),
                taxId: taxId,
                price: [
                    {
                        currencyId: currencyId,
                        net: 42,
                        linked: false,
                        gross: 64
                    }
                ]
            }
        );
        return cy.request({
            headers: {
                Accept: 'application/vnd.api+json',
                Authorization: `Bearer ${auth.access}`,
                'Content-Type': 'application/json'
            },
            method: 'POST',
            url: '/api/_action/sync',
            qs: {
                response: true
            },
            body: {
                'write-product': {
                    'entity': 'product',
                    'action': 'upsert',
                    'payload': products
                }

            }
        })
    })
});

Cypress.Commands.add('createStoreLocatorFixture', (storeLocatorData) => {
    let countryId, countryStateId, salesChannelId, storeLocatorJson, storeLocatorRawData;

    cy.fixture('store').then((result) => {
        storeLocatorJson = Cypress._.merge(result, storeLocatorData);

        return cy.searchViaAdminApi({
            endpoint: 'sales-channel',
            data: {
                field: 'name',
                value: 'Raiffeisen-Markt'
            }
        });
    }).then((result) => {
        salesChannelId = result.id;

        return cy.searchViaAdminApi({
            data: {
                field: 'iso',
                value: 'DE'
            },
            endpoint: 'country'
        });
    }).then((result) => {
        countryId = result.id

        return cy.searchViaAdminApi({
            data: {
                field: 'shortCode',
                value: 'DE-NW'
            },
            endpoint: 'country-state'
        });
    }).then((result) => {
        countryStateId = result.id

        return cy.getRandomMedia();
    }).then((result) => {
        storeLocatorRawData = Cypress._.merge(storeLocatorJson, {
            id: uuid().replace(/-/g, ''),
            countryId: countryId,
            countryStateId: countryStateId,
            pictureMediaId: result[0].id,
            salesChannels: [{
                id: salesChannelId
            }]
        });

        return cy.createViaAdminApi({
            data: storeLocatorRawData,
            endpoint: 'neti-store-locator'
        });
    }).then(() => {
        return storeLocatorRawData
    })
})

Cypress.Commands.add('isNotInViewport', element => {
    cy.get(element).then($el => {
        const bottom = Cypress.$(cy.state('window')).height()
        const rect = $el[0].getBoundingClientRect()
        expect(rect.top).to.be.greaterThan(bottom)
        expect(rect.bottom).to.be.greaterThan(bottom)
        expect(rect.top).to.be.greaterThan(bottom)
        expect(rect.bottom).to.be.greaterThan(bottom)
    })
});

Cypress.Commands.add('isInViewport', element => {
    cy.get(element).then($el => {
        const bottom = Cypress.$(cy.state('window')).height()
        const rect = $el[0].getBoundingClientRect()
        expect(rect.top).not.to.be.greaterThan(bottom)
        expect(rect.bottom).not.to.be.greaterThan(bottom)
        expect(rect.top).not.to.be.greaterThan(bottom)
        expect(rect.bottom).not.to.be.greaterThan(bottom)
    })
});

/**
 * Returns random product with id, name and url to view product
 * @memberOf Cypress.Chainable#
 * @name getRandomProductInformationForCheckout
 * @function
 */
Cypress.Commands.add('getRandomProduct', () => {
    return cy.storefrontApiRequest('GET', 'product', {}, {}).then((result) => {
        return result.body.elements[0];
    });
});

/**
 * Returns random product with id, name and url to view product
 * @memberOf Cypress.Chainable#
 * @name getRandomProductInformationForCheckout
 * @function
 */
Cypress.Commands.add('getRandomMedia', () => {
    const apiClient = new AdminApiService(process.env.APP_URL)
    return apiClient.post(`/search/media?response=true`, {
        limit: 10,
        associations: {
            "pixelboxxMedia": { }
        },
        filter: [{
            field: 'pixelboxxMedia.pixelboxxId',
            type: 'contains',
            value: 'pboxx'
        }]
    }, { limit: 10 }).then((response) => {
        return response;
    });
});

Cypress.Commands.add('getApiVersion', () => {
    cy.authenticate().then((auth) => {
        return cy.request({
            headers: {
                Accept: 'application/vnd.api+json',
                Authorization: `Bearer ${auth.access}`,
                'Content-Type': 'application/json'
            },
            method: 'GET',
            url: '/api/_info/version',
            qs: {
                response: true
            },
            body: {}
        });
    });
})