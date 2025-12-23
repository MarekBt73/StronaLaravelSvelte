<script>
    let {
        src,
        alt = '',
        class: className = '',
        loading = 'lazy',
        aspectRatio = null
    } = $props();

    // Generate responsive srcset from base path
    function generateSrcset(basePath) {
        if (!basePath) return '';

        // Extract path without extension
        const lastDot = basePath.lastIndexOf('.');
        const pathWithoutExt = basePath.substring(0, lastDot);
        const ext = basePath.substring(lastDot + 1);

        // Check if WebP versions exist by trying to generate srcset
        const sizes = [
            { name: 'mobile', width: 480 },
            { name: 'tablet', width: 768 },
            { name: 'desktop', width: 1200 }
        ];

        return sizes
            .map(s => `${pathWithoutExt}-${s.name}.webp ${s.width}w`)
            .join(', ');
    }

    function getWebPSrc(basePath) {
        if (!basePath) return basePath;

        const lastDot = basePath.lastIndexOf('.');
        const pathWithoutExt = basePath.substring(0, lastDot);

        return `${pathWithoutExt}-desktop.webp`;
    }

    const srcset = generateSrcset(src);
    const webpSrc = getWebPSrc(src);
    const sizes = '(max-width: 480px) 480px, (max-width: 768px) 768px, 1200px';
</script>

<picture>
    {#if srcset}
        <source
            srcset={srcset}
            sizes={sizes}
            type="image/webp"
        />
    {/if}
    <img
        src={src}
        {alt}
        class={className}
        {loading}
        style={aspectRatio ? `aspect-ratio: ${aspectRatio}` : ''}
    />
</picture>
