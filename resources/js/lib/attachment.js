export const MAX_FILES = 5;
export const MAX_FILE_SIZE = 5 * 1024 * 1024;
export const MAX_TOTAL_SIZE = 25 * 1024 * 1024;

export const ALLOWED_TYPES = [
    "image/jpeg",
    "image/png",
    "application/pdf",
    "application/msword",
    "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
];

// Memvalidasi dan memilih file
export const handleFileChange = (event, currentFiles = []) => {
    const selectedFiles = Array.from(event.target.files ?? []);

    const newFiles = [...currentFiles];

    for (const file of selectedFiles) {
        if (newFiles.length >= MAX_FILES) {
            return {
                files: newFiles,
                error: "Maksimal 5 file lampiran.",
            };
        }

        if (!ALLOWED_TYPES.includes(file.type)) {
            return {
                files: newFiles,
                error: `${file.name}: Format file tidak didukung.`,
            };
        }

        if (file.size > MAX_FILE_SIZE) {
            return {
                files: newFiles,
                error: `${file.name}: Ukuran file maksimal 5 MB.`,
            };
        }

        const totalSize = [...newFiles, file].reduce(
            (total, item) => total + item.size,
            0,
        );

        if (totalSize > MAX_TOTAL_SIZE) {
            return {
                files: newFiles,
                error: "Total ukuran lampiran maksimal 25 MB.",
            };
        }

        newFiles.push(file);
    }

    return {
        files: newFiles,
        error: null,
    };
};

// Menghapus file berdasarkan index
export const removeAttachment = (files, index) => {
    return files.filter((_, i) => i !== index);
};
