import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";

registerBlockType("jci-blocks/ad-area-one", {
  title: __("Ad Area One", "jci-blocks"),
  category: "jci-category",
  icon: "admin-links",
  keywords: [__("jci-blocks")],
  description: __("Ad space appearing at top of page", "jci-blocks"),
  deprecated: [
    {
      save() {
        return (
          <div className='wp-block-jci-blocks-ad-area-one jci-block-placeholder'>
            <p>Ad Area One Placeholder</p>
          </div>
        );
      },
    },
    {
      save: () => {
        return (
          <div id='div-gpt-ad-1568929479747-0'>
            {/* 14503085/CordlessMedia_Livability.com_ROS_ATF_970x250 */}
            {window.googletag &&
              googletag.cmd.push(function () {
                googletag.display("div-gpt-ad-1568929479747-0");
              })}
          </div>
        );
      },
    }
  ],
  edit: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>Ad Area One Placeholder</p>
      </div>
    );
  },
  save: () => {
    return null;
  },
});
