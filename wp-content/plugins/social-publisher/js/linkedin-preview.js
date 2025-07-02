(function ($) {
	// console.log('LinkedIn Admin Preview Script Initialized.');

	// Function to update the LinkedIn preview
	function updatePreview(updateTextOnly = false) {
		let cachedImageUrl = null; // Cache for the image URL
		let cachedText = ''; // Cache for the text content
		// console.log(`--- updatePreview() called --- Update Mode: ${updateTextOnly ? 'Text Only' : 'Full'}`);
		const customTextField = $(
			'textarea[name="acf[field_linkedin_custom_text]"]'
		);
		const imageField = $('input[name="acf[field_linkedin_image]"]');
		const previewContainer = $('#linkedin-preview');

		if (!customTextField.length || !previewContainer.length) {
			console.warn(
				'Required fields or preview container not found. Exiting updatePreview.'
			);
			return;
		}

		const customText = customTextField.val() || '';
		const imageId = imageField.length ? imageField.val() : '';

		// console.log(`Current Custom Text: "${customText}"`);
		// console.log(`Current Image ID: "${imageId}"`);
		// console.log(`Cached Image URL: "${cachedImageUrl}"`);

		// Update text content
		if (updateTextOnly) {
			if (customText !== cachedText) {
				// console.log('Text has changed. Updating text in the preview.');
				const formattedText = customText
					.split('\n\n') // Separate paragraphs
					.map(
						(paragraph) =>
							`<p>${paragraph.replace(/\n/g, '<br>')}</p>`
					) // Handle single line breaks
					.join('');
				cachedText = customText; // Update text cache

				const textContainer = previewContainer.find(
					'.linkedin-text-preview'
				);
				if (textContainer.length) {
					textContainer.html(formattedText);
				} else {
					previewContainer.prepend(
						`<div class="linkedin-text-preview">${formattedText}</div>`
					);
				}
			} else {
				// console.log('Text has not changed. Skipping text update.');
			}
			return;
		}

		// Handle image preview
		if (imageId) {
			if (!cachedImageUrl || cachedImageUrl.imageId !== imageId) {
				// console.log(`Fetching image URL for new Image ID: "${imageId}"`);
				$.ajax({
					url: linkedinPreview.ajaxurl,
					method: 'POST',
					data: {
						action: 'get_image_url',
						image_id: imageId,
						nonce: linkedinPreview.nonce,
					},
					success(response) {
						// console.log('AJAX Response:', response);
						if (
							response &&
							response.success &&
							response.data.url
						) {
							cachedImageUrl = {
								url: response.data.url,
								imageId,
							}; // Cache the image URL
							updateImagePreview(
								previewContainer,
								cachedImageUrl.url
							);
						}
					},
					error(error) {
						console.error('Error fetching image URL:', error);
					},
				});
			} else {
				// console.log('Using cached image URL for Image ID:', imageId);
				updateImagePreview(previewContainer, cachedImageUrl.url);
			}
		} else {
			console.warn('No image ID provided. Removing image preview.');
			previewContainer.find('.linkedin-image-preview').remove();
		}
	}

	// Helper function to update the image preview
	function updateImagePreview(previewContainer, imageUrl) {
		const imageContainer = previewContainer.find(
			'.linkedin-image-preview'
		);
		if (imageContainer.length) {
			imageContainer.attr('src', imageUrl);
		} else {
			previewContainer.append(
				`<img class="linkedin-image-preview" src="${imageUrl}" alt="LinkedIn Preview Image" style="max-width: 300px; height: auto; margin-top: 15px;" />`
			);
		}
		// console.log('Updated image preview with URL:', imageUrl);
	}

	// Attach event listeners for textarea
	function attachTextListeners() {
		const customTextField = $(
			'textarea[name="acf[field_linkedin_custom_text]"]'
		);
		if (customTextField.length) {
			// console.log('Attaching listeners to the textarea...');
			customTextField.on('input change', function () {
				// console.log('Custom Text Field Updated:', $(this).val());
				updatePreview(true); // Update only the text part
			});
		} else {
			console.warn('Textarea not found, retrying...');
			setTimeout(attachTextListeners, 500); // Retry after 500ms
		}
	}

	// Attach event listeners for image field
	function attachImageListeners() {
		const imageField = $('input[name="acf[field_linkedin_image]"]');
		if (imageField.length) {
			// console.log('Attaching listeners to the image field...');
			imageField.on('input change', function () {
				// console.log('Image Field Updated. New Value:', $(this).val());
				cachedImageUrl = null; // Reset cache if the image field changes
				updatePreview(); // Trigger preview update on image change
			});

			// Trigger initial image update if the field already has a value
			if (imageField.val()) {
				// console.log('Initial Image Field Value:', imageField.val());
				updatePreview();
			}
		} else {
			console.warn('Image field not found, retrying...');
			setTimeout(attachImageListeners, 500); // Retry after 500ms
		}
	}

	// Function to wait for fields before initializing
	function waitForFields() {
		// console.log('Waiting for ACF fields...');
		const customTextField = $(
			'textarea[name="acf[field_linkedin_custom_text]"]'
		);
		const imageField = $('input[name="acf[field_linkedin_image]"]');
		const previewContainer = $('#linkedin-preview');

		if (
			customTextField.length &&
			imageField.length &&
			previewContainer.length > 0
		) {
			// console.log('ACF fields are now available.');
			attachTextListeners();
			attachImageListeners();
			updatePreview(); // Initial preview update
		} else {
			console.warn('ACF fields not yet available, retrying...');
			setTimeout(waitForFields, 500); // Retry every 500ms
		}
	}

	// Initialize the script
	$(document).ready(function () {
		// console.log('LinkedIn Admin Preview Script Initialized.');
		waitForFields();
	});
})(jQuery);
