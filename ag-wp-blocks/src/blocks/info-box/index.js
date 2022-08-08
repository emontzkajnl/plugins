import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import { RichText, useBlockProps } from "@wordpress/block-editor";
import { SelectControl, Button, TextControl } from "@wordpress/components";

registerBlockType("jci-blocks/info-box", {
  title: __("Info Box", "jci-blocks"),
  category: "jci-category",
  icon: "admin-links",
  keywords: [__("jci-blocks")],
  description: __("Info Box", "jci-blocks"),
  attributes: {
    text: {
      type: "string",
      source: "html",
      selector: "p",
    },
    name: {
      type: "string",
      source: "html",
      selector: ".info-box-name",
    },
    position: {
      type: "string",
      source: "html",
      selector: ".info-box-position",
    },
    icon: {
      type: "string",
      default: "quotes",
    },
    buttonText: {
      type: "string",
      source: "text",
      selector: "a",
    },
    buttonLink: {
      type: "string",
      source: "attribute",
      attribute: "href",
      selector: "a",
    }
  },
  deprecated: [
    { 
      attributes: {
        text: {
          type: "string",
          source: "html",
          selector: "p",
        },
        name: {
          type: "string",
          source: "html",
          selector: ".info-box-name",
        },
        position: {
          type: "string",
          source: "html",
          selector: ".info-box-position",
        },
        icon: {
          type: "string",
          default: "quotes",
        }
      },
      save: ({ attributes }) => {
        const blockProps = useBlockProps.save();
        // const { text, name, position, icon } = attributes;
        return (
          <div className={attributes.icon}>
            <RichText.Content
              tagName='p'
              className='info-box-quote'
              value={attributes.text}
            />
            <RichText.Content
              tagName='p'
              className='info-box-name'
              value={attributes.name}
            />
            <RichText.Content
              tagName='p'
              className='info-box-position'
              value={attributes.position}
            />
          </div>
        );
      },
    }
  ],
  edit: ({ attributes, setAttributes }) => {
    console.log('attributes edit ',attributes);
    return (
      <>
        <SelectControl
          label={__("Select Icon", "jci-blocks")}
          value={attributes.icon}
          onChange={(icon) => {
            setAttributes({ icon });
          }}
          options={[
            { label: "quotes", value: "quotes" },
            { label: "active", value: "active" },
            { label: "adventure", value: "adventure" },
            { label: "city", value: "city" },
            { label: "dollar", value: "dollar" },
            { label: "education", value: "education" },
            { label: "food", value: "food" },
            { label: "fun fact", value: "fun-fact" },
            { label: "health", value: "health" },
            { label: "link", value: "link" },
            { label: "logo", value: "logo" },
            { label: "love", value: "love" },
            { label: "metro", value: "metro" },
            { label: "music", value: "music" },
            { label: "neighborhood", value: "neighborhood" },
            { label: "nightlife", value: "nightlife" },
            { label: "pets", value: "pets" },
            { label: "question mark", value: "question-mark" },
            { label: "sports", value: "sports" },
            { label: "tourism", value: "tourism" },
            { label: "transportation", value: "transportation" },
          ]}
        />
        <RichText
          tagName='p'
          className='info-box-quote'
          value={attributes.text}
          onChange={(text) => setAttributes({ text })}
          placeholder={__("Quote...", "jci_blocks")}
        />
        <RichText
          tagName='p'
          className='info-box-name'
          value={attributes.name}
          onChange={(name) => setAttributes({ name })}
          placeholder={__("Name...", "jci_blocks")}
        />
        <RichText
          tagName='p'
          className='info-box-position'
          value={attributes.position}
          onChange={(position) => setAttributes({ position })}
          placeholder={__("Position...", "jci_blocks")}
        />
        <TextControl
        label = "Button Text (optional)"
        value={attributes.buttonText}
        onChange={(buttonText) => setAttributes({buttonText})}
        />
        <TextControl
        label = "Button Link (optional)"
        value={attributes.buttonLink}
        onChange={(buttonLink) => setAttributes({buttonLink})}
        />

      </>
    );
  },
  save: ({ attributes }) => {
    const blockProps = useBlockProps.save();
    console.log('attributes save ',attributes);
    const { text, name, position, icon, buttonText, buttonLink } = attributes;

    return (
      <div className={icon}>
        <RichText.Content
          tagName='p'
          className='info-box-quote'
          value={text}
        />
        <RichText.Content
          tagName='p'
          className='info-box-name'
          value={name}
        />
        <RichText.Content
          tagName='p'
          className='info-box-position'
          value={position}
        />
        { buttonText && buttonLink && (
        <Button
          href={buttonLink}
          target="_blank"
          text={buttonText}
          className='info-box-button'
        />
        )}
        
      </div>
    );
  },
});
