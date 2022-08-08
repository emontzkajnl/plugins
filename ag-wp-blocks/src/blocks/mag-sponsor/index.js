import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

registerBlockType("jci-blocks/mag-sponsor", {
  title: __("Magazine Sponsor", "jci-blocks"),
  category: "jci-category",
  icon: "admin-links",
  keywords: [__("jci-blocks")],
  edit: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Magazine Sponsor</p>
      </div>
    );
  },
  save: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Magazine Sponsor</p>
      </div>
    );
  },
});
