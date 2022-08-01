const autoprefixer = require("autoprefixer");
const MiniCSSExtractPlugin = require("mini-css-extract-plugin");
// eslint-disable-next-line no-undef
const webpack = require("webpack");

module.exports = (env, argv) => {
  function isDevelopment() {
    return argv.mode === "development";
  }
  var config = {
    entry: {
      editor: "./src/editor.js",
      script: "./src/script.js",
      jcicustom: "./src/jcicustom.js",
    },
    output: {
      filename: "[name].js",
    },
    plugins: [
      new MiniCSSExtractPlugin({
        // chunkFilename: "[id].css",
        chunkFilename: (chunk) => {
          return chunk.name === "script" ? "style.css" : "[name].css";
        },
      }),
      new webpack.ProvidePlugin({
        process: "process/browser",
      }),
    ],
    devtool: isDevelopment() ? "cheap-module-source-map" : "source-map",
    module: {
      rules: [
        {
          test: /\.js$/,
          exclude: /node_modules/,
          use: {
            loader: "babel-loader",
            options: {
              plugins: ["@babel/plugin-proposal-class-properties"],
              presets: [
                "@babel/preset-env",
                [
                  "@babel/preset-react",
                  {
                    pragma: "wp.element.createElement",
                    pragmaFrag: "wp.element.Fragment",
                    development: isDevelopment(),
                  },
                ],
              ],
            },
          },
        },
        {
          test: /\.(sa|sc|c)ss$/,
          use: [
            MiniCSSExtractPlugin.loader,
            "css-loader",
            {
              loader: "postcss-loader",
              options: {
                postcssOptions: {
                  plugins: [autoprefixer()],
                },
              },
            },
            "sass-loader",
          ],
        },
      ],
    },
    externals: {
      jquery: "jQuery",
      "@wordpress/blocks": ["wp", "blocks"],
      "@wordpress/i18n": ["wp", "i18n"],
      "@wordpress/editor": ["wp", "editor"],
      "@wordpress/block-editor": ["wp", "blockEditor"],
      "@wordpress/components": ["wp", "components"],
      "@wordpress/element": ["wp", "element"],
      "@wordpress/data": ["wp", "data"],
      "@wordpress/api-fetch": ["wp", "api-fetch"],
      "@wordpress/compose": ["wp", "compose"],
    },
    resolve: {
      alias: {
        path: require.resolve("path-browserify"),
      },
    },
    stats: {
      errorDetails: true,
    },
  };
  return config;
};
