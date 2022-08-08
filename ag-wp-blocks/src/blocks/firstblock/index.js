var registerBlockType = wp.blocks.registerBlockType;
var __ = wp.i18n.__;
var el = wp.element.createElement;

registerBlockType("jci-blocks/first-block", {
  title: __("First Block", "jci_blocks"),
  category: "layout",
  icon: "admin-network",
  keywords: [__("photo", "jci-blocks")],
  edit: function () {
    return el("p", null, "Editor");
  },
  save: function () {
    return el("p", null, "Save");
  },
});
