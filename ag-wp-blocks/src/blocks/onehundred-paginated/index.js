import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import { SelectControl } from "@wordpress/components";
import { InnerBlocks } from "@wordpress/block-editor";

registerBlockType("jci-blocks/onehundred-paginated", {
  title: __("One Hundred Paginated", "jci-blocks"),
  category: "jci-category",
  icon: "admin-links",
  keywords: [__("jci-blocks")],
  attributes: {
    page: {
        type: "number",

    }
  },
  edit: ({ attributes, setAttributes }) => {
    return (
      <>
      <SelectControl
          label={__("Select Page", "jci-blocks")}
          value={attributes.page}
          onChange={(page) => {
            setAttributes({ page });
          }}
          
          options={[
            { label: "1", value: "1" },
            { label: "2", value: "2" },
            { label: "3", value: "3" },
            { label: "4", value: "4" },
            { label: "5", value: "5" },
            { label: "6", value: "6" },
            { label: "7", value: "7" },
            { label: "8", value: "8" },
            { label: "9", value: "9" },
            { label: "10", value: "10" },
          ]}
        />
        {/* <div className='jci-block-placeholder'>
          <p>One Hundred Paginated Placeholder</p>
        </div> */}
        {/* <InnerBlocks /> */}
      </>
    );
  },
  save: (props) => {
    // {
    //   console.log("props are ", props);
    // }
    return (
      <div className='jci-block-placeholder'>
        <p>One Hundred Paginated Placeholder</p>
        {/* <InnerBlocks.Content /> */}
      </div>
    );
  },
});
