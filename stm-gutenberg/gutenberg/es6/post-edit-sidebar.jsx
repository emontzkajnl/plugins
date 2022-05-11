const { PluginSidebar, PluginSidebarMoreMenuItem } = wp.editPost;
const {registerPlugin} = wp.plugins;

const MyPluginSidebar = () => (
    <PluginSidebar
        name="stm-gutenberg/pl-sbr"
        title="My sidebar title"
        icon="smiley"
    >
        <PanelBody>
            { __( 'My sidebar content' ) }
        </PanelBody>
    </PluginSidebar>
);


console.log(13);
registerPlugin( 'stm-gutenberg/pl-sbr', {
    icon: 'smiley',
    render: MyPluginSidebar,
} );

wp.data.dispatch( 'core/edit-post' ).openGeneralSidebar( 'stm-gutenberg/pl-sbr' );