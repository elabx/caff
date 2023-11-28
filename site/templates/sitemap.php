<?php echo "<?xml version='1.0' encoding='UTF-8'?>
<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>";
$map = $pages->find("template=article|articles|basic-page|events|experts|home|landing|project|projects");
foreach ($map as $item){
    $modified = date('Y-m-d',$item->modified);
    echo "<url>
    <loc>https://caff.is{$item->url}</loc>
        <lastmod>{$modified}</lastmod>
    </url>";
}
echo "</urlset>";
?>