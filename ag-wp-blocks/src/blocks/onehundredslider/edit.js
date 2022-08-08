import { Component } from "@wordpress/element";
import { __ } from "@wordpress/i18n";
// import { withSelect } from "@wordpress/data";
import Slider from "react-slick";
// import "slick-carousel/slick/slick.css";
// import "slick-carousel/slick/slick-theme.css";
const { withSelect } = wp.data;

class LatestPostsEdit extends Component {
  render() {
    const { posts, className } = this.props;
    const settings = {
      //   infinite: false,
      //   speed: 500,
      dots: true,
      slidesToShow: 1,
      slidesToScroll: 1,
    };
    return (
      <>
        <div>
          Slider code here.
          {/* <Slider {...settings}>
          <h3>Test A</h3>

          <h3>Test B</h3>

          <h3>Test C</h3>
        </Slider> */}
        </div>
      </>
    );
  }
}

// export default withSelect((select, props) => {
//   const { attributes } = props;
//   const { numberOfPosts } = attributes;
//   let query = { per_page: numberOfPosts };
//   return {
//     posts: select("core").getEntityRecords("postType", "page", query),
//   };
// })(LatestPostsEdit);
export default LatestPostsEdit;

//  {/* {posts && posts.length > 0 ? ( */}

// {
/* {posts.map((post) => (
              <li key={post.id}>
                <a href={post.link}>{post.title.rendered}</a>
              </li>
            ))} */
// }

// ) : (
//   <div>
//     {posts
//       ? __("No posts found", "jci_blocks")
//       : __("Loading...", "jci_blocks")}
//   </div>
// )}
