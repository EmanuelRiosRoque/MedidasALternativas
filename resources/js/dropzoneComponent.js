export default function dropzoneComponent(acceptedTypes = '', maxSize = 10485760, inputName = 'archivos') {
    return {
        files: [],
        dragging: false,
        inputName,

        handleDrop(e) {
            this.dragging = false;
            const dropped = Array.from(e.dataTransfer.files);
            this.addFiles(dropped);
        },

        handleFileChange(e) {
            const selected = Array.from(e.target.files);
            this.addFiles(selected);
        },

        addFiles(fileList) {
            fileList.forEach(file => {
                const extension = file.name.toLowerCase();
                const allowed = acceptedTypes === '' || acceptedTypes.split(',').some(type => extension.endsWith(type.trim()));
                const validSize = file.size <= maxSize;

                if (!allowed || !validSize) {
                    alert(`Archivo no permitido o excede el tamaño máximo: ${file.name}`);
                    return;
                }

                const reader = new FileReader();
                reader.onload = (event) => {
                    this.files.push({
                        name: file.name,
                        size: file.size,
                        type: file.type,
                        preview: file.type.startsWith('image/') ? event.target.result : null
                    });
                };
                reader.readAsDataURL(file);
            });
        },

        removeFile(index) {
            this.files.splice(index, 1);
        },

        formatSize(bytes) {
            return bytes >= 1048576
                ? (bytes / 1048576).toFixed(1) + ' MB'
                : (bytes / 1024).toFixed(1) + ' KB';
        }
    };
}
