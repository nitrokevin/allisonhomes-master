wp.domReady(() => {
    const { addFilter } = wp.hooks;
    const { createHigherOrderComponent } = wp.compose;
    const { Fragment, useEffect } = wp.element;
    const { InspectorControls } = wp.blockEditor;
    const { PanelBody, MediaUpload, MediaUploadCheck } = wp.components;

    // Add background image control
    const withBackgroundImage = createHigherOrderComponent((BlockEdit) => {
        return (props) => {
            if (props.name !== 'core/column') {
                return <BlockEdit {...props} />;
            }
            const { attributes, setAttributes } = props;
            const { backgroundImage, backgroundImageId } = attributes;

            useEffect(() => {}, [backgroundImage]);

            return (
                <Fragment>
                    <BlockEdit {...props} />
                    <InspectorControls>
                        <PanelBody title="Background Image Settings">
                            <MediaUploadCheck>
                                <MediaUpload
                                    onSelect={(media) =>
                                        setAttributes({
                                            backgroundImageId: media.id,
                                            backgroundImage: media.sizes?.medium?.url || media.url,
                                        })
                                    }
                                    allowedTypes={['image']}
                                    value={backgroundImageId}
                                    render={({ open }) => (
                                        <button
                                            className="components-button is-secondary"
                                            onClick={open}
                                        >
                                            {backgroundImage
                                                ? 'Change Background Image'
                                                : 'Select Background Image'}
                                        </button>
                                    )}
                                />
                            </MediaUploadCheck>
                            {backgroundImage && (
                                <button
                                    className="components-button is-secondary"
                                    onClick={() =>
                                        setAttributes({ backgroundImage: '', backgroundImageId: 0 })
                                    }
                                >
                                    Remove Background Image
                                </button>
                            )}
                        </PanelBody>
                    </InspectorControls>
                </Fragment>
            );
        };
    }, 'withBackgroundImage');

    // Add backgroundImage attribute
    addFilter(
        'blocks.registerBlockType',
        'my-plugin/column-background',
        (settings, name) => {
            if (name !== 'core/column') {
                return settings;
            }
            return {
                ...settings,
                attributes: {
                    ...settings.attributes,
                    backgroundImage: { type: 'string', default: '' },
                    backgroundImageId: { type: 'number', default: 0 },
                },
            };
        }
    );

    // Apply the control
    addFilter('editor.BlockEdit', 'my-plugin/column-background', withBackgroundImage);

    // Add inline styles in editor
    addFilter(
        'editor.BlockListBlock',
        'my-plugin/column-background-style',
        createHigherOrderComponent((BlockListBlock) => {
            return (props) => {
                if (props.name !== 'core/column') {
                    return <BlockListBlock {...props} />;
                }
                const { attributes } = props;
                const { backgroundImage } = attributes;

                return (
                    <BlockListBlock
                        {...props}
                        key={backgroundImage}
                        wrapperProps={{
                            style: backgroundImage
                                ? {
                                      backgroundImage: `url(${backgroundImage})`,
                                      backgroundSize: 'cover',
                                      backgroundPosition: 'center',
                                      backgroundRepeat: 'no-repeat',
                                  }
                                : {},
                        }}
                    />
                );
            };
        }, 'withBackgroundImageStyle')
    );
});