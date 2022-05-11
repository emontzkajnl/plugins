const path = require('path');
const webpack = require('webpack');

const config = {
    entry: {
        'theia-post-slider': './assets/js/_bundle.js',
        'carousel-for-theia-post-slider': './assets/js/_carousel-bundle.js'
    },
    target: ['web', 'es5'],
    output: {
        filename: 'js/[name].js',
        path: path.resolve(__dirname, 'dist')
    },
    module: {
        rules: [
            {
                test: /\.(js|jsx)$/,
                loader: 'babel-loader'
            }
        ]
    },
    resolve: {
        extensions: [
            '.js',
            '.jsx'
        ]
    },
    // optimization: {
    //     runtimeChunk: {
    //         name: 'runtime'
    //     }
    // }
};

module.exports = config;
