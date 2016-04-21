<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Requestik;
use Symfony\Component\HttpFoundation\Response;


class HttpRequestsController extends Controller
{
    /**
     * @Route("/new_time_entry", name="new_time_entry")
     */
    public function newTimeEntryAction(Request $request)
    {
        $client = new Client([
            'base_uri' => 'https://redmine.ekreative.com/',
            'auth' => ['test', '9uu82T487m6V41G'],
            //'headers' => ['X-Redmine-API-Key' => '2fda745bb4cdd835fdf41ec1fab82a13ddc1a54c'],
            'headers' => ['Content-Type' => 'application/json'],
        ]);
        $client->post('time_entries.json', [
            'json' => [
                'time_entry' => [
                    'hours' => 1.999,
                    'project_id' => 378,
                    'activity_id' => 11,
                    'comments' => 'It works!!!',
                ]],
        ]);

        return new Response('OK');


    }

    /**
     * @Route("/issues", name="issues")
     */
    public function issuesAction(Request $request)
    {
        $client = new Client([
            'base_uri' => 'https://redmine.ekreative.com/',
            'auth' => ['test', '9uu82T487m6V41G'],
            //'headers' => ['X-Redmine-API-Key' => '2fda745bb4cdd835fdf41ec1fab82a13ddc1a54c'],
            'headers' => ['Content-Type' => 'application/json'],
        ]);
        $requestik = new Requestik('GET', 'issues.json?project_id=378');
        $responsik = $client->send($requestik);
        $projects = json_decode($responsik->getBody(), true);

        return $this->render(':http:issues.html.twig', array(
            'projects' => $projects,
        ));
    }

    /**
     * @Route("/projects", name="projects")
     */
    public function projectsAction(Request $request)
    {
        $client = new Client([
            'base_uri' => 'https://redmine.ekreative.com/',
            'auth' => ['test', '9uu82T487m6V41G'],
            //'headers' => ['X-Redmine-API-Key' => '2fda745bb4cdd835fdf41ec1fab82a13ddc1a54c'],
            'headers' => ['Content-Type' => 'application/json'],
        ]);
        $requestik = new Requestik('GET', 'projects.json');
        $responsik = $client->send($requestik);
        $projects = json_decode($responsik->getBody(), true);

        return $this->render(':http:projects.html.twig', array(
            'projects' => $projects,
        ));
    }

    /**
     * @Route("/time_entries", name="time_entries")
     */
    public function timeEntriesAction(Request $request)
    {
        $client = new Client([
            'base_uri' => 'https://redmine.ekreative.com/',
            'auth' => ['test', '9uu82T487m6V41G'],
            //'headers' => ['X-Redmine-API-Key' => '2fda745bb4cdd835fdf41ec1fab82a13ddc1a54c'],
            'headers' => ['Content-Type' => 'application/json'],
        ]);
        $requestik = new Requestik('GET', 'time_entries.json');
        $responsik = $client->send($requestik);
        $data = json_decode($responsik->getBody(), true);

        return $this->render(':http:time_entries.html.twig', array(
            'time_entries' => $data['time_entries'],
        ));
    }

}

