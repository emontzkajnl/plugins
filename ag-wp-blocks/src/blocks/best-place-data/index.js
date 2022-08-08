import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

registerBlockType("jci-blocks/best-place-data", {
  title: __("Best Place Data", "jci-blocks"),
  category: "jci-category",
  icon: "leftright",
  keywords: [__("best place", "data", "jci-blocks")],
  description: __(
    "FOR BEST PLACES CHILDREN ONLY - displays data about place",
    "jci-blocks"
  ),
  edit: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Best Place Data Placeholder</p>
      </div>
    );
  },
  save: () => {
    return null;
  },
});
