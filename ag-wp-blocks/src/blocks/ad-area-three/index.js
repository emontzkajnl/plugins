import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

registerBlockType("jci-blocks/ad-area-three", {
  title: __("Ad Area Three", "jci-blocks"),
  category: "jci-category",
  icon: "admin-links",
  keywords: [__("jci-blocks")],
  deprecated: [
    {
      save() {
        return (
          <div className='jci-block-placeholder'>
            <p>Ad Area Three Placeholder</p>
          </div>
        );
      },
    },
    {
      save: () => {
        return (
          <div id='div-gpt-ad-1568929556599-0'>
            {window.googletag &&
              googletag.cmd.push(function () {
                googletag.display("div-gpt-ad-1568929556599-0");
              })}
          </div>
        );
      },
    }
  ],
  edit: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Ad Area Three Placeholder</p>
      </div>
    );
  },
  save: () => {
    return null;
  },
});
