// import { registerBlockType } from "@wordpress/blocks";
// import { __ } from "@wordpress/i18n";
// import { useBlockProps, RichText } from "@wordpress/block-editor";

// registerBlockType("jci-blocks/quote", {
//   title: __("JCI Quote", "jci-blocks"),
//   category: "jci-category",
//   icon: "admin-links",
//   keywords: [__("jci-blocks")],
//   description: __("Displays blockquote inside main content", "jci-blocks"),
//   attributes: {
//     quote: {
//       type: string,
//     },
//   },
//   edit: ({ attributes, setAttributes }) => {
//     const blockProps = useBlockProps();
//     return (
//       <RichText
//         {...blockProps}
//         tagName='blockquote'
//         value={attributes.content}
//         onChange={(content) => setAttributes({ content })}
//         placeholder={__("Quote...")}
//       />
//     );
//   },
//   save: ({ attributes }) => {
//     const blockProps = useBlockProps.save();
//     return (
//       <RichText.Content
//         {...blockProps}
//         tagName='blockquote'
//         value={attributes.content}
//       />
//     );
//   },
// });
