***
MLL Gallery custom plugin information
Files included: 
mll_gallery.php The main plugin file
nosidebar.php A page template for the gallery pages without a sidebar (or use your own)
mll_gallery.css css for the gallery images

Basic description:
The plugin creates a custom post type of "mll_gallery_post" and a page called "Portfolio" with the slug "portfolio-page" if the page does not already exist. The page includes a shortcode to display all the items with the custom post type of "mll_gallery_post".

The gallery posts are intended to include only a title and image, and only the image displays on the gallery page by default. Custom "media" taxonomy is included which may be assigned to the post for use in creating shortcodes for additional gallery pages.

Additional gallery pages can be created by adding the shortcode at the desired location, and gallery pages can be edited to include additional content before or after the gallery section.

Shorcode usage:
the shortcode is [gallery_grid]
Attributes may be set using any of the "Media" assigned to the gallery posts.
Example:
[gallery_grid mll_media = "illustration"]

Things to fix: 
There is still some issue around the shortcode including an attribute, and the JSON error. 