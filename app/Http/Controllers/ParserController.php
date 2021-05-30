<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use duzun\hQuery;
use Goutte\Client;


class ParserController extends Controller
{
    public function form(Request $request)
    {
        $data = $request->all();
        if ($data) {
            //парсим 1 линк
            $url_1 = hQuery::fromUrl($data['url_1'], ['Accept' => 'text/html,application/xhtml+xml;q=0.9,*/*;q=0.8']);
            //парсим 2 линк
            $url_2 = hQuery::fromUrl($data['url_2'], ['Accept' => 'text/html,application/xhtml+xml;q=0.9,*/*;q=0.8']);



            $client = new Client();

            $crawler = $client->request('GET',$data['url_2']);

            $pattern_token = "/_token: '(.*)'/";

            $pattern_user_id = '/:user_id="(.*)"/';
            preg_match($pattern_token, $crawler->html(), $matches_token);

            $_token = $matches_token[1];

            preg_match($pattern_user_id, $crawler->html(), $matches_user_id);

            $user_id = $matches_user_id[1];
            $data = [
                'question_id' => '',
                'user_id' => $user_id,
                'offset' => 0,
                '_token' => $_token
            ];

            $content = http_build_query($data);

            $crawler = $client->request('POST', 'https://scitylanamda.asktape.com/user/answers', [], [], ['HTTP_CONTENT_TYPE' => 'application/x-www-form-urlencoded'], $content);

            //print_r(json_decode($client->getResponse()->getContent())); die;
            $QAV = [];
            foreach ($url_1->find('p[class=question-q]') as $question) {
                if (is_array(json_decode($client->getResponse()->getContent())))
                    foreach (json_decode($client->getResponse()->getContent()) as $question2) {
                        if ($question2->question->message == $question) {
                            $urlVideo = 'https://scitylanamda.asktape.com/' . $question2->user->nickname . '/video/' . $question2->code . '/';
                            $crawlerVideo = $client->request('GET', $urlVideo);
                            $QAV[] = ['question' => $question2->question->message,
                                'video_url' => $crawlerVideo->filter('#popup-play-video')->first()->attr('src')];
                        }
                    }
            }
            return view('form', ['QAV' => $QAV]);
        }

        return view('form');
    }
}