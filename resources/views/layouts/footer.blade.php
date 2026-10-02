<script>
const deleteImage = async (collection, id, singleImageField) => {
        const docRef = database.collection(collection).doc(id);
        try {
            const docSnapshot = await docRef.get();
            var imageUrl = '';
                if (!docSnapshot.exists) return;
                if (singleImageField) {
                    const data = docSnapshot.data();
                    imageUrl = data[singleImageField];
                    if (imageUrl) await deleteImageFromBucket(imageUrl);
                }
                
        } catch (error) {
            console.error("Error deleting image:", error);
        }
    };

const deleteMultipleImages  = async (collection, id, arrayImageField) => {
    const docRef = database.collection(collection).doc(id);
    try {
        const docSnapshot = await docRef.get();
        if (!docSnapshot.exists) return;
        if (arrayImageField) {
                if (Array.isArray(arrayImageField)) {
                    for (const field of arrayImageField) {
                        if (field && Array.isArray(field)) {
                            for (const imageUrl of field) {
                                if (imageUrl) await deleteImageFromBucket(imageUrl);
                            }
                        }
                    }
                } else {
                    const arrayImages = docSnapshot.data()[arrayImageField];
                    if (arrayImages && Array.isArray(arrayImages)) {
                        for (const imageUrl of arrayImages) {
                            if (imageUrl) await deleteImageFromBucket(imageUrl);
                        }
                    }
                }
            }
        } catch (error) {
            console.error("Error deleting image:", error);
        }
};
const deleteImageFromBucket = async (imageUrl) => {
    try {
        const storage = firebase.storage();
        const oldImageUrlRef = storage.refFromURL(imageUrl);
        // Get bucket name from the image reference
        var imageBucket = oldImageUrlRef.bucket;
        // Get the bucket name from environment variables (configured in your project)
        var envBucket = "<?php echo env('FIREBASE_STORAGE_BUCKET'); ?>";
        if (imageBucket === envBucket) {
            await oldImageUrlRef.delete();
        }
        console.log("Image deleted successfully.");
    } catch (error) {
        console.error("Error deleting image:", error);
    }
};
</script>