import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

registerBlockType("jci-blocks/ad-area-two", {
  title: __("Ad Area Two", "jci-blocks"),
  category: "jci-category",
  icon: "admin-links",
  keywords: [__("jci-blocks")],
  description: __("Ad space appearing in right sidebar", "jci-blocks"),
  deprecated: [
    {
      save() {
        return (
          <div className='wp-block-jci-blocks-ad-area-two jci-block-placeholder'>
            <p>Ad Area Two Placeholder</p>
          </div>
        );
      },
    },
    {
      save: () => {
        return (
          <div id='div-gpt-ad-1568929535248-0'>
            {window.googletag &&
              googletag.cmd.push(function () {
                googletag.display("div-gpt-ad-1568929535248-0");
              })}
          </div>
        );
      },
    }
  ],
  edit: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Ad Area Two Placeholder</p>
      </div>
    );
  },
  save: () => {
    return null;
  },
});
