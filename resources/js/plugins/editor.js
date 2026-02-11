window.loadEditor = async () => {
    const { default: ClassicEditor } = await import('@ckeditor/ckeditor5-build-classic')

    document.querySelectorAll('.editor').forEach(el => {
        ClassicEditor.create(el)
    })
}
