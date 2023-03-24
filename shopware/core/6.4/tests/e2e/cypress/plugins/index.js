/* eslint-disable */

// ***********************************************************
// This example plugins/index.js can be used to load plugins
//
// You can change the location of this file or turn off loading
// the plugins file with the 'pluginsFile' configuration option.
//
// You can read more here:
// https://on.cypress.io/plugins-guide
// ***********************************************************

// This function is called when a project is opened or re-opened (e.g. due to
// the project's config changing)

const axios = require('axios');
require('@babel/register');

// TODO Check incompatibility and reintegrate as soon as possible
// const logToOutput = require('cypress-log-to-output');

module.exports = (on, config) => {
    // logToOutput.install(on);

    // `on` is used to hook into various events Cypress emits
    on('before:browser:launch', () => {
        config.env.projectRoot = config.env.projectRoot || config.env.shopwareRoot;
    });

    return config;
};