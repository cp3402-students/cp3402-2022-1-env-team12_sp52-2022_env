/**
 * BLOCK: gosign-text-with-image-block
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

/**
 * External dependencies
 */
import classnames from "classnames";

//  Import CSS.
import "./style.scss";
import "./editor.scss";
import icon from "./icon.js";

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks

const { Component, Fragment } = wp.element;
const { PanelBody, RangeControl, SelectControl, Toolbar } = wp.components;
const {
	InspectorControls,
	BlockAlignmentToolbar,
	PanelColorSettings,
	BlockControls,
	RichText,
	InnerBlocks,
	AlignmentToolbar
} = wp.editor;

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType("gosign/block-gosign-text-with-image-block", {
	title: __("Gosign - Text with Image Block"),
	icon: icon.textWithImage ,
	category: "common",
	keywords: [
		__("text with image block"),
		__("image block"),
		__("inline text with image")
	],
	attributes: {
		headline: {
			type: "string"
		},
		rubrik: {
			type: "string"
		},
		subheadline: {
			type: "string"
		},
		headlineSize: {
			type: "string",
			default: ""
		},
		bodytext: {
			type: "string"
		},
		headingAlignment: {
			type: "string",
			default: "center"
		},
		textAlignment: {
			type: "string",
			default: "center"
		},
		headlineColor: {
			type: "string",
			default: "#000000"
		},
		rubrikColor: {
			type: "string",
			default: "#000000"
		},
		subheadlineColor: {
			type: "string",
			default: "#000000"
		},
		textColor: {
			type: "string",
			default: "#000000"
		},
		image: {
			type: "string"
		},
		imageCaption: {
			type: "string",
			source: "text",
			selector: "figcaption"
		},
		imagePosition: {
			type: "string",
			default: "0"
		},
		horizontalPos: {
			type: "string",
			default: "center"
		},
		verticalPos: {
			type: "string",
			default: "above"
		},
		noWrap: {
			type: "boolean",
			default: false
		},
		align: {
			type: "string",
			default: "full"
		},
		imageColumnWidth: {
			type: "int",
			default: 50
		}
	},

	getEditWrapperProps(attributes) {
		const { align } = attributes;
		if (
			"left" === align ||
			"right" === align ||
			"wide" === align ||
			"full" === align
		) {
			return { "data-align": align };
		}
	},

	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * The "edit" property must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	edit: function(props) {
		const { attributes, setAttributes } = props;
		const {
			align,
			imagePosition,
			horizontalPos,
			verticalPos,
			noWrap,
			headline,
			headlineSize,
			headingAlignment,
			textColor,
			bodytext,
			imageColumnWidth,
			headlineColor,
			textAlignment,
			rubrik,
			subheadline,
			rubrikColor,
			subheadlineColor
		} = attributes;

		const $availableGalleryPositions = [
			{
				horizontal: {
					center: [0, 8],
					right: [1, 9, 17, 25],
					left: [2, 10, 18, 26]
				}
			},
			{
				vertical: {
					above: [0, 1, 2],
					intext: [17, 18, 25, 26],
					below: [8, 9, 10]
				}
			}
		];

		const findInArray = (array, number) => {
			const found = array.find(function(no) {
				return no == number;
			});

			return found;
		};

		const widthRangeControl = () => {
			if (verticalPos == "intext") {
				return (
					<RangeControl
						label="In Text Image Width"
						value={imageColumnWidth}
						onChange={width =>
							setAttributes({
								imageColumnWidth: width
							})
						}
						min={0}
						max={100}
					/>
				);
			}
		};

		const inspectorControls = (
			<InspectorControls>
				<PanelBody title={__("Text With Image Settings")}>
					<SelectControl
						label="Position and Alignment "
						value={imagePosition}
						options={[
							{ label: "Above Center", value: "0" },
							{ label: "Above Right", value: "1" },
							{ label: "Above Left", value: "2" },
							{ label: "Below Center", value: "8" },
							{ label: "Below Right", value: "9" },
							{ label: "Below Left", value: "10" },
							{ label: "In text Right", value: "17" },
							{ label: "In text Left", value: "18" },
							{ label: "Beside Right", value: "25" },
							{ label: "Beside Left", value: "26" }
						]}
						onChange={imagePosition => {
							$availableGalleryPositions.map((position, index) => {
								if (position.horizontal != undefined) {
									if (
										findInArray(position.horizontal.center, imagePosition) !=
										undefined
									) {
										setAttributes({ horizontalPos: "center" });
									}
									if (
										findInArray(position.horizontal.right, imagePosition) !=
										undefined
									) {
										setAttributes({ horizontalPos: "right" });
									}
									if (
										findInArray(position.horizontal.left, imagePosition) !=
										undefined
									) {
										setAttributes({ horizontalPos: "left" });
									}
								} else {
									if (
										findInArray(position.vertical.above, imagePosition) !=
										undefined
									) {
										setAttributes({ verticalPos: "above" });
									}
									if (
										findInArray(position.vertical.intext, imagePosition) !=
										undefined
									) {
										setAttributes({ verticalPos: "intext" });
									}
									if (
										findInArray(position.vertical.below, imagePosition) !=
										undefined
									) {
										setAttributes({ verticalPos: "below" });
									}
								}
								if (imagePosition == 25 || imagePosition == 26) {
									setAttributes({ noWrap: true });
								} else {
									if (noWrap != false) {
										setAttributes({ noWrap: false });
									}
								}
							});
							setAttributes({ imagePosition: imagePosition });
						}}
					/>
					{widthRangeControl()}
					<SelectControl
						label="Select Headline Size"
						value={headlineSize}
						options={[
							{ label: "H1", value: "h1" },
							{ label: "H2", value: "h2" }
						]}
						onChange={size => {
							setAttributes({ headlineSize: size });
						}}
					/>
					<h3>Heading Alignment</h3>
					<AlignmentToolbar
						value={headingAlignment}
						onChange={function(alignment) {
							props.setAttributes({ headingAlignment: alignment });
						}}
					/>
					<h3>Text Alignment</h3>
					<AlignmentToolbar
						value={textAlignment}
						onChange={function(alignment) {
							props.setAttributes({ textAlignment: alignment });
						}}
					/>
					<PanelColorSettings
						title={__("Color Settings")}
						colorSettings={[
							{
								value: rubrikColor,
								onChange: colorValue =>
									props.setAttributes({ rubrikColor: colorValue }),
								label: __("Rubrik Color")
							}
						]}
					/>
					<PanelColorSettings
						title={__("Color Settings")}
						colorSettings={[
							{
								value: textColor,
								onChange: colorValue =>
									props.setAttributes({ headlineColor: colorValue }),
								label: __("Headline Color")
							}
						]}
					/>
					<PanelColorSettings
						title={__("Color Settings")}
						colorSettings={[
							{
								value: subheadlineColor,
								onChange: colorValue =>
									props.setAttributes({ subheadlineColor: colorValue }),
								label: __("Subhealine Color")
							}
						]}
					/>
					<PanelColorSettings
						title={__("Color Settings")}
						colorSettings={[
							{
								value: textColor,
								onChange: colorValue =>
									props.setAttributes({ textColor: colorValue }),
								label: __("Text Color")
							}
						]}
					/>
				</PanelBody>
			</InspectorControls>
		);

		const renderImageBlock = () => {
			if (horizontalPos == "center") {
				return (
					<div className="ce-gallery">
						<div class="ce-outer">
							<div class="ce-inner">
								<div className="ce-row">
									<div className="ce-column">
										<InnerBlocks
											template={[["core/image", {}]]}
											templateLock="all"
										/>
									</div>
								</div>
							</div>
						</div>
					</div>
				);
			} else {
				return (
					<div
						className="ce-gallery"
						style={{
							width: verticalPos == "intext" ? imageColumnWidth + "%" : "auto"
						}}
					>
						<div className="ce-row">
							<div className="ce-column">
								<InnerBlocks
									template={[["core/image", {}]]}
									templateLock="all"
								/>
							</div>
						</div>
					</div>
				);
			}
		};

		return (
			<Fragment>
				| {inspectorControls}
				<BlockControls>
					<BlockAlignmentToolbar
						value={align}
						onChange={nextAlign => {
							setAttributes({ align: nextAlign });
						}}
						controls={["center", "full"]}
					/>
				</BlockControls>
				{noWrap != true && (
					<header>
						<RichText
							tagName="h4"
							className={props.className}
							value={rubrik}
							style={{ textAlign: headingAlignment, color: rubrikColor }}
							placeholder="add your rubrik here"
							// formattingControls={[]}
							onChange={function(content) {
								setAttributes({ rubrik: content });
							}}
						/>
						<RichText
							tagName={headlineSize ? headlineSize : "h1"}
							className={props.className}
							value={headline}
							style={{ textAlign: headingAlignment, color: headlineColor }}
							placeholder="add your headline here"
							// formattingControls={[]}
							onChange={function(content) {
								setAttributes({ headline: content });
							}}
						/>
						<RichText
							tagName="h3"
							className={props.className}
							value={subheadline}
							style={{ textAlign: headingAlignment, color: subheadlineColor }}
							placeholder="add your subheadline here"
							// formattingControls={[]}
							onChange={function(content) {
								setAttributes({ subheadline: content });
							}}
						/>
					</header>
				)}
				<div
					className={classnames(props.className, "ce-textpic", {
						[`ce-${horizontalPos}`]: true,
						[`ce-${verticalPos}`]: true,
						"no-wrap": noWrap
					})}
				>
					{verticalPos != "below" && <Fragment>{renderImageBlock()}</Fragment>}
					<div class="ce-bodytext">
						{noWrap == true && (
							<header>
								<RichText
									tagName="h4"
									className={props.className}
									value={rubrik}
									style={{ textAlign: headingAlignment, color: rubrikColor }}
									placeholder="add your rubrik here"
									// formattingControls={[]}
									onChange={function(content) {
										setAttributes({ rubrik: content });
									}}
								/>
								<RichText
									tagName={headlineSize ? headlineSize : "h1"}
									className={props.className}
									value={headline}
									style={{ textAlign: headingAlignment, color: headlineColor }}
									placeholder="add your headline here"
									// formattingControls={[]}
									onChange={function(content) {
										setAttributes({ headline: content });
									}}
								/>
								<RichText
									tagName="h3"
									className={props.className}
									value={subheadline}
									style={{
										textAlign: headingAlignment,
										color: subheadlineColor
									}}
									placeholder="add your subheadline here"
									// formattingControls={[]}
									onChange={function(content) {
										setAttributes({ subheadline: content });
									}}
								/>
							</header>
						)}
						<RichText
							tagName="p"
							className={props.className}
							value={bodytext}
							style={{ textAlign: textAlignment, color: textColor }}
							placeholder="add your paragraph here"
							// formattingControls={[]}
							onChange={function(content) {
								setAttributes({ bodytext: content });
							}}
						/>
					</div>
					{verticalPos == "below" && <Fragment>{renderImageBlock()}</Fragment>}
				</div>
			</Fragment>
		);
	},

	/**
	 * The save function defines the way in which the different attributes should be combined
	 * into the final markup, which is then serialized by Gutenberg into post_content.
	 *
	 * The "save" property must be specified and must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	save: function(props) {
		const { attributes } = props;
		const {
			horizontalPos,
			verticalPos,
			noWrap,
			headline,
			headlineSize,
			textColor,
			bodytext,
			imageColumnWidth,
			headlineColor,
			textAlignment,
			headingAlignment,
			rubrik,
			subheadline,
			rubrikColor,
			subheadlineColor
		} = attributes;
		const textStyle = {
			display: noWrap == true ? "table" : "block"
		};
		const renderImageBlockSave = () => {
			if (horizontalPos == "center") {
				return (
					<div className="ce-gallery">
						<div class="ce-outer">
							<div class="ce-inner">
								<div className="ce-row">
									<div className="ce-column">
										<InnerBlocks.Content />
									</div>
								</div>
							</div>
						</div>
					</div>
				);
			} else {
				return (
					<div
						className="ce-gallery"
						style={{
							width: verticalPos == "intext" ? imageColumnWidth + "%" : "auto"
						}}
					>
						<div className="ce-row">
							<div className="ce-column">
								<InnerBlocks.Content />
							</div>
						</div>
					</div>
				);
			}
		};
		return (
			<Fragment>
				{noWrap != true && (
					<header>
						<RichText.Content
							tagName="h4"
							className={props.className}
							value={rubrik}
							style={{ textAlign: headingAlignment, color: rubrikColor }}
						/>
						<RichText.Content
							tagName={headlineSize ? headlineSize : "h1"}
							className={props.className}
							value={headline}
							style={{ textAlign: headingAlignment, color: headlineColor }}
						/>
						<RichText.Content
							tagName="h3"
							className={props.className}
							value={subheadline}
							style={{ textAlign: headingAlignment, color: subheadlineColor }}
						/>
					</header>
				)}
				<div
					className={classnames(props.className, "ce-textpic", {
						[`ce-${horizontalPos}`]: true,
						[`ce-${verticalPos}`]: true,
						"no-wrap": noWrap
					})}
				>
					{verticalPos != "below" && (
						<Fragment>{renderImageBlockSave()}</Fragment>
					)}
					<div className="ce-bodytext">
						{noWrap == true && (
							<header>
								<RichText.Content
									tagName="h4"
									className={props.className}
									value={rubrik}
									style={{ textAlign: headingAlignment, color: rubrikColor }}
								/>
								<RichText.Content
									tagName={headlineSize ? headlineSize : "h1"}
									className={props.className}
									value={headline}
									style={{ textAlign: headingAlignment, color: headlineColor }}
								/>
								<RichText.Content
									tagName="h3"
									className={props.className}
									value={subheadline}
									style={{
										textAlign: headingAlignment,
										color: subheadlineColor
									}}
								/>
							</header>
						)}
						<RichText.Content
							tagName="p"
							className={props.className}
							value={bodytext}
							style={{ textAlign: textAlignment, color: textColor }}
						/>
					</div>
					{verticalPos == "below" && (
						<Fragment>{renderImageBlockSave()}</Fragment>
					)}
				</div>
			</Fragment>
		);
	}
});
