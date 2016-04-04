<?php


namespace Dingbat\Action\Card;

use Dingbat\Action;
use Dingbat\Model\Card;
use Dingbat\Model\Task;
use Slim\Http\Request;
use Slim\Http\Response;

class Delete extends Action\AbstractImpl
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function run(Request $request, Response $response, array $args) : Response
    {
        try {
            // find card
            /* @var Card $card */
            $card = Card::query()->findOrFail($args['id']);

            // delete tasks of card
            Task::query()->where('cardId', $card->id);

            // delete card
            $card->delete();
        } catch (\Exception $e) {
            // not needed. respose code is always 204
        }

        return $response->withStatus(204);
    }

}

