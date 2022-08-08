import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
// import Slider from "react-slick";

registerBlockType("jci-blocks/onehundredslider", {
  title: __("One Hundred Slider Block", "jci-blocks"),
  category: "jci-category",
  icon: "leftright",
  keywords: [__("carousel, sliders", "jci-blocks")],

  edit: () => {
    return (
      <div className='jci-block-placeholder'>
        <p>One Hundred Slider Placeholder</p>
      </div>
    );
  },
  save: () => {
    return null;
  },
});
