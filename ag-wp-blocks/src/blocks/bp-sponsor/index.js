import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

registerBlockType("jci-blocks/bp-sponsor", {
  title: __("Best Place Sponsor", "jci-blocks"),
  category: "jci-category",
  icon: "admin-links",
  keywords: [__("jci-blocks")],
  description: __("Displays best place sponsor only if one is added to acf on page", "jci-blocks"),
  edit: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Best Place Sponsor</p>
      </div>
    );
  },
  save: () => {
    return null;
  },
});