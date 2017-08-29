<?php
namespace Plugin\InfoTownMedia\Service;

/**
 * Parse response form WordPress via WP API.
 * @package Plugin\InfoTownMedia\Service
 */
class WpApiResponseService
{
    /**
     * Parse response of posts.  Response is created by Guzzle HTTP Client from WP API response.
     * @param array $data
     * @return array
     */
    public function parsePosts($data = [])
    {
        $posts = [];
        foreach ($data as $item) {
            array_push(
                $posts,
                [
                    'id'      => $item['id'],
                    'title'   => $item['title']['rendered'],
                    'content' => $item['content']['rendered'],
                ]
            );
        }

        return $posts;
    }

    /**
     * Parse response of a post.  Response is created by Guzzle HTTP Client from WP API response.
     * @param $data
     * @return array
     */
    public function parsePost($data)
    {
        return [
            0 => [
                'title'   => $data['title']['rendered'],
                'content' => $data['content']['rendered'],
            ],
        ];
    }

    /**
     * Parse response of posts.  Response is created by Guzzle HTTP Client from WP API response.
     * @param array $data
     * @return array
     */
    public function parseMedia($data = [], $format = 'img')
    {
        $imgs = [];
        foreach ($data as $item) {
            $img = $this->getHtmlImageElement($item);
            array_push($imgs, $img);
        }

        return $imgs;
    }

    /**
     * Get img tag src attribute is set.
     * @param array $data
     * @return array
     */
    public function getHtmlImageElement($data)
    {
        return "<img src='".$data["guid"]["rendered"] ."'>";
    }
}