import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

registerBlockType("jci-blocks/city-301", {
  title: __("City 301", "jci-blocks"),
  category: "jci-category",
  icon: "admin-links",
  keywords: [__("jci-blocks")],
  description: __("Message for city redirects from old livability. Use on state pages only. Links to city list block. ", "jci-blocks"),
  edit: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>City 301 Placeholder</p>
      </div>
    );
  },
  save: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>City 301 Placeholder</p>
      </div>
    );
  },
});