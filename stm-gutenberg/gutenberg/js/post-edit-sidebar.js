const {
  PluginSidebar,
  PluginSidebarMoreMenuItem
} = wp.editPost;
const {
  registerPlugin
} = wp.plugins;

const MyPluginSidebar = () => React.createElement(PluginSidebar, {
  name: "stm-gutenberg/pl-sbr",
  title: "My sidebar title",
  icon: "smiley"
}, React.createElement(PanelBody, null, __('My sidebar content')));

console.log(13);
registerPlugin('stm-gutenberg/pl-sbr', {
  icon: 'smiley',
  render: MyPluginSidebar
});
wp.data.dispatch('core/edit-post').openGeneralSidebar('stm-gutenberg/pl-sbr');