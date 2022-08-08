import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

registerBlockType("jci-blocks/link-place-to-top-100", {
  title: __("Link Place to Top 100", "jci-blocks"),
  category: "jci-category",
  icon: "leftright",
  keywords: [__("best place", "data", "jci-blocks")],
  description: __(
    "For client cities only - links to recent top one hundred list if it was ranked.",
    "jci-blocks"
  ),
  edit: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Link Place to Top 100 Placeholder</p>
      </div>
    );
  },
  save: () => {
    return null;
  },
});
