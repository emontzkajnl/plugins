import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

registerBlockType("jci-blocks/breadcrumbs", {
  title: __("Breadcrumbs", "jci-blocks"),
  category: "jci-category",
  icon: "admin-links",
  keywords: [__("jci-blocks")],
  edit: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Breadcrumbs Placeholder</p>
      </div>
    );
  },
  save: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Breadcrumbs Placeholder</p>
      </div>
    );
  },
});
