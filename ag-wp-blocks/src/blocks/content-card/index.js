import "./styles.editor.scss";
import { registerBlockType } from "@wordpress/blocks";
import { __ } from "@wordpress/i18n";
import { withSelect } from "@wordpress/data";
import { useBlockProps, RichText } from "@wordpress/block-editor";
// import { Autocomplete, SelectControl } from "@wordpress/components";
import { withState } from "@wordpress/compose";
import apiFetch from "@wordpress/api-fetch";

const attributes = {
  postId: {
    type: "number",
  },
  postTitle: {
    type: "string",
  },
};

registerBlockType("jci-blocks/contentcard", {
  title: __("Content Card", "jci-blocks"),
  category: "jci-category",
  icon: "admin-links",
  keywords: [__("card, content", "jci-blocks")],
  attributes,
  edit: withSelect((select, props) => {
    // console.log("props are ", props);
    return {
      posts: select("core").getEntityRecords("postType", "post"),
      props,
    };
  })(({ posts, props }) => {
    // load all posts
    // add autocomplete Component
    // setAttribute on change
    const blockProps = useBlockProps();
    // console.log("block props ", blockProps);
    // console.log("props ", props);
    // console.log("posts: ", posts);
    const { attributes, setAttributes } = props;
    // const onPostIdChange = (id) => {
    //   console.log("post id change func ran");
    //   setAttributes({ postId: id });
    // };

    // ? posts.map((p) => {
    //     return { id: p.id, label: p.title.rendered };
    //   })
    // let selectOptions = {};
    const selectOptions = posts
      ? posts.map((p) => {
          // return `${p.id}--${p.title.rendered}`;
          return { id: p.id, label: p.title.rendered };
        })
      : null;
    // ? posts.map((p) => {
    //     selectOptions[p.title.rendered] = p.id;
    //   })
    // : null;

    const autocompleteConfig = [
      {
        name: "Select Post",
        triggerPrefix: "~",
        options: selectOptions,
        getOptionLabel: (option) => <span>{option.label}</span>,
        getOptionKeywords: (option) => [option.label, option.id],
        getOptionCompletion: (option) => {
          console.log(`id is ${option.id} and label is ${option.label}`);
          setAttributes({
            postId: parseInt(option.id, 10),
            postTitle: option.label,
          });
          return option.label;
        },
      },
    ];

    // const MySelectControl = withState({ postId: 0 })(({ setState }) => (
    //   <SelectControl
    //     label='Posts'
    //     value={attributes.postId}
    //     options={selectOptions}
    //     onChange={(postId) => {
    //       setState({ postId }), setAttributes({ postId: parseInt(postId, 10) });
    //     }}
    //   />
    // ));

    return (
      <div {...blockProps}>
        {!posts && "Loading..."}
        {posts && posts.length === 0 && "No Posts"}
        {posts && posts.length > 0 && (
          //     // <a href={posts[0].link}>{posts[0].title.rendered}</a>
          // <SelectControl
          //   label={__("Select post", "jci_blocks")}
          //   value='249'
          //   onChange={onPostIdChange}
          //   options={selectOptions}
          // />
          <>
            {console.log("selectOptions: ", selectOptions)}
            <RichText
              autocompleters={autocompleteConfig}
              value={attributes.postTitle}
              onChange={(newValue) => {
                console.log(newValue);
                setAttributes({
                  postTitle: newValue,
                });
              }}
              placeholder='Dbl Clk, then press "~"'
            />
          </>
        )}
      </div>
    );
  }),
});
