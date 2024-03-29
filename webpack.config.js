const path = require("path");
const { VueLoaderPlugin } = require("vue-loader");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const isProductionMode = process.env.NODE_ENV === "production";
const CopyPlugin = require("copy-webpack-plugin");

module.exports = {
  mode: isProductionMode ? "production" : "development",
  entry: {
    ticketFeed: "./vue/ticketfeed/feed.js",
    artGallery: "./vue/gallery/gallery.js",
    tailwind: "./assets/css/style.css",
  },
  output: {
    path: path.resolve(__dirname, "public/assets/"),
    filename: "js/[name].js",
  },
  plugins: [
    new VueLoaderPlugin(),
    new MiniCssExtractPlugin({
      filename: "css/style.css",
    }),
    new CopyPlugin({
      patterns: [
        { from: "assets/sound", to: "sound" }, { from: "assets/img", to: "img" }],
    }),
  ],
  resolve: {
    alias: {
      vue: "vue/dist/vue.esm-bundler.js",
    },
    extensions: ["*", ".js", ".vue", ".json"],
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
        },
      },
      {
        test: /\.vue$/,
        loader: "vue-loader",
      },
      {
        test: /\.css$/,
        use: [MiniCssExtractPlugin.loader, "css-loader", "postcss-loader"],
      },
    ],
  },
};
