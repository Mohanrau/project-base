const Encore = require('@symfony/webpack-encore');
const EventHooksPlugin = require('event-hooks-webpack-plugin');
const processTrans = require('./assets/js/commands/translations/process');
const generateWebFont = require('./assets/js/commands/svg/generateWebFont');
const CopyPlugin = require('copy-webpack-plugin');
const yaml = require('js-yaml');
const fs = require('fs');
const path = require('path');
const StylelintPlugin = require('stylelint-webpack-plugin');
const sources = require('./assets/js/bin/helpers/sources');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('web/build/')
    .setPublicPath('/build')
    .setManifestKeyPrefix('web')
    .cleanupOutputBeforeBuild()
    .autoProvidejQuery()
    .addEntry('frontend', './assets/js/frontend.js')
    // hp entry?
    // order entry?
    // product entry?
    // cart entry?
    .addEntry('styleguide', './assets/js/styleguide/styleguide.js')
    .addEntry('admin', './assets/js/admin/admin.js')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabel(() => {}, {
        useBuiltIns: 'usage',
        corejs: 3
    })
    .enableBuildNotifications()
    .configureWatchOptions(function (watchOptions) {
        watchOptions.ignored = '**/*.json';
    })
    .addPlugin(new EventHooksPlugin({
        beforeRun: () => {
            generateWebFont(
                'frontend',
                './assets/public/frontend/svg/*.svg'
            );
            generateWebFont(
                'admin',
                './assets/public/admin/svg/*.svg'
            );

            const dirWithJsFiles = [
                sources.getFrameworkNodeModulesDir() + '/js/**/*.js',
                './assets/js/**/*.js'
            ];
            const dirWithTranslations = [
                sources.getFrameworkVendorDir() + '/src/Resources/translations/*.po',
                './translations/*.po',
            ];
            const outputDirForExportedTranslations = Encore.isProduction() ? './web/build/' : './assets/js/';

            try {
                processTrans(dirWithJsFiles, dirWithTranslations, outputDirForExportedTranslations);
            } catch (e) {
                console.log('Parsing files for translations has failed.');
            }
        }
    }))
    .addPlugin(new CopyPlugin([
        { from: 'web/bundles/fpjsformvalidator', to: '../../assets/js/bundles/fpjsformvalidator', force: true },
        { from: 'assets/public', to: '../../web/public', force: true },
        { from: 'node_modules/@shopsys/framework/public/svg/admin', to: '../../web/public/admin/svg', force: true }
    ]))
;

const domainFile = './config/domains.yml';
const domains = yaml.safeLoad(fs.readFileSync(domainFile, 'utf8'));

domains.domains.forEach((domain) => {
    if (!domain.styles_directory) {
        domain.styles_directory = 'common';
    }
    Encore
        .addEntry('frontend-style-' + domain.styles_directory, './assets/styles/frontend/' + domain.styles_directory + '/main.less')
        .addEntry('frontend-print-style-' + domain.styles_directory, './assets/styles/frontend/' + domain.styles_directory + '/print/main.less')
        .addEntry('frontend-wysiwyg-' + domain.id, './assets/styles/frontend/' + domain.styles_directory + '/wysiwyg.less');
});

Encore
    .addEntry('admin-style', './assets/styles/admin/main.less')
    .addEntry('admin-wysiwyg', './assets/styles/admin/wysiwyg.less')
    .addEntry('styleguide-style', './assets/styles/styleguide/main.less')
    .addPlugin(
        new StylelintPlugin({
            configFile: '.stylelintrc',
            files: 'assets/styles/**/*.less'
        })
    )
    .enableLessLoader()
    .enablePostCssLoader()
;

const config = Encore.getWebpackConfig();

config.resolve.alias = {
    'jquery-ui': 'jquery-ui/ui/widgets',
    'framework': '@shopsys/framework/js',
    'jquery': path.resolve(path.join(__dirname, 'node_modules', 'jquery')),
    'jquery-ui-styles': path.resolve(path.join(__dirname, 'node_modules', 'jquery-ui')),
    'bazinga-translator': path.resolve(path.join(__dirname, 'node_modules', 'bazinga-translator'))
};
module.exports = config;
