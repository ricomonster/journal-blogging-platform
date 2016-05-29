<?php //-->
namespace Journal\Support;

class MetaGenerator
{
    protected $meta;

    /**
     * Generates the meta based on the given data and type
     *
     * @param  array|object|null $data
     * @param  string|null $type
     * @return this
     */
    public function generate($data = null, $type = null)
    {
        // set the default data
        $metaData = [
            'site_url'      => url('/'),
            'title'         => blog_title(),
            'type'          => 'website',
            'description'   => blog_description(),
            'url'           => url('/'),
            // figure out how to get the settings
            'image_url'     => ''
        ];

        // check if there's a given type
        if (!is_null($type)) {
            switch ($type) {
                case 'author' :
                    $metaData['title']          = $data->name . ' - ' . blog_title();
                    $metaData['type']           = 'profile';
                    $metaData['description']    = null;
                    $metaData['url']            = url('author/' . $data->slug);

                    if ($data->cover_url) {
                        $metaData['image_url']      = (strpos($data->cover_url, 'http')) ?
                            $data->cover_url : url($data->cover_url);
                    }

                    break;
                case 'post' :
                    $metaData['title']          = $data->title;
                    $metaData['type']           = 'article';
                    $metaData['description']    = excerpt($data);
                    $metaData['url']            = url($data->slug);

                    if ($data->cover_image) {
                        $metaData['image_url']      = (strpos($data->cover_image, 'http')) ?
                            $data->cover_image : url($data->cover_image);
                    }

                    break;
                case 'tag' :
                    $metaData['title']          = $data->title;
                    $metaData['type']           = 'website';
                    $metaData['description']    = $data->description;
                    $metaData['url']            = url('tag/' . $data->slug);

                    if ($data->cover_image) {
                        $metaData['image_url']      = (strpos($data->cover_image, 'http')) ?
                            $data->cover_image : url($data->cover_image);
                    }

                    break;
            }
        }

        // now prepare the data that we're going to return
        $this->generateMetaTree($metaData);

        return $this;
    }

    /**
     * Returns the generate meta data
     *
     * @return array
     */
    public function get()
    {
        return $this->meta;
    }

    /**
     * Returns the generate meta data on a template
     *
     * @return View
     */
    public function withTemplate()
    {
        return view('vendor.meta', ['metaTree' => $this->meta]);
    }

    /**
     * Generates the meta tree based on the given meta data
     *
     * @param array $metaData
     * @return void
     */
    private function generateMetaTree($metaData)
    {
        $this->meta =  [
            'link' => [
                'rel' => [
                    [
                        'attribute' => 'canonical',
                        'href'  => $metaData['url']
                    ]
                ]
            ],
            'meta' => [
                'name' => [
                    [
                        'attribute' => 'referrer',
                        'content' => 'origin'
                    ],
                    [
                        'attribute' => 'twitter:card',
                        'content' => 'summary'
                    ],
                    [
                        'attribute' => 'twitter:title',
                        'content' => $metaData['title']
                    ],
                    [
                        'attribute' => 'twitter:description',
                        'content' => $metaData['description']
                    ],
                    [
                        'attribute' => 'twitter:url',
                        'content' => $metaData['url']
                    ],
                    [
                        'attribute' => 'twitter:image:src',
                        'content' => $metaData['image_url']
                    ],
                    [
                        'attribute' => 'generator',
                        'content' => 'Journal v0.0.5'
                    ]
                ],
                'property' => [
                    [
                        'attribute' => 'og:site_name',
                        'content' => $metaData['title']
                    ],
                    [
                        'attribute' => 'og:type',
                        'content' => $metaData['type']
                    ],
                    [
                        'attribute' => 'og:title',
                        'content' => $metaData['title']
                    ],
                    [
                        'attribute' => 'og:description',
                        'content' => $metaData['description'],
                    ],
                    [
                        'attribute' => 'og:url',
                        'content' => $metaData['url']
                    ],
                    [
                        'attribute' => 'og:image',
                        'content' => $metaData['image_url']
                    ]
                ]
            ]
        ];
    }
}
