<?php
namespace Plugin\InfoTownLinkWp\Service;

/**
 * Parse response form WordPress via WP API.
 * @package Plugin\InfoTownLinkWp\Service
 */
class WpApiResponseService
{
    /**
     * Parse response of posts. Response is created by Guzzle HTTP Client from WP API response.
     * @param array $posts Response body from WP API.
     * @param string $format data type. contents or links.
     * @return array
     */
    public function parsePosts($posts = [], $format = 'contents')
    {
        if ($format === 'links') {
            $data = $this->makeLinks($posts);

            return $data;
        }
        $data = $this->makeContents($posts);

        return $data;
    }

    /**
     * Parse response of a post. Response is created by Guzzle HTTP Client from WP API response.
     * @param array $post Response body from WP API.
     * @param string $format data type. contents or links.
     * @return array
     */
    public function parsePost($post, $format = 'contents')
    {
        if ($format === 'links') {
            $data = $this->makeLink($post);

            return $data;
        }
        $data = $this->makeContent($post);

        return $data;
    }

    /**
     * Make contents.
     * @param array $posts Response body from WP API.
     * @return array Post contents to display.
     */
    private function makeContents($posts)
    {
        $contents = [];
        foreach ($posts as $item) {
            array_push(
                $contents,
                [
                    'id'      => $item['id'],
                    'title'   => $item['title']['rendered'],
                    'content' => $item['content']['rendered'],
                ]
            );
        }

        return $contents;
    }

    /**
     * Make content.
     * @param array $post Post data from WP API.
     * @return array Post content to display.
     */
    public function makeContent($post)
    {
        return [
            0 => [
                'id'      => $post['id'],
                'title'   => $post['title']['rendered'],
                'content' => $post['content']['rendered'],
            ],
        ];
    }

    /**
     * Make links.
     * @param array $posts Response body from WP API.
     * @return array Post contents to display.
     */
    private function makeLinks($posts)
    {
        $links = [];
        foreach ($posts as $item) {
            array_push(
                $links,
                [
                    'link' => '<a href="'.$item['link'].'">'.$item['title']['rendered'].'</a>',
                ]
            );
        }

        return $links;
    }

    /**
     * Make link.
     * @param array $post Response body from WP API.
     * @return array Post link to display.
     */
    private function makeLink($post)
    {
        return [
            0 => [
                'link' => '<a href="'.$post['link'].'">'.$post['title']['rendered'].'</a>',
            ],
        ];
    }
}