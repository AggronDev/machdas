<?php


namespace Dingbat\Action\Task;

use Dingbat\Action;
use Dingbat\Model\Card;
use Dingbat\Model\Task;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class Update
 *
 * @category Action
 * @package  Dingbat\Action\Task
 * @author   Pierre Klink <dev@klinks.info>
 * @license  MIT http://opensource.org/licenses/MIT
 * @link     https://github.com/pklink/Dingbat
 */
class Update implements Action
{

    const CODE_ALL_FINE = 0;
    const CODE_TASK_DOES_NOT_EXIST = 1;
    const CODE_CARD_ID_IS_NOT_GIVEN = 2;
    const CODE_CARD_DOES_NOT_EXIST = 3;
    const CODE_NAME_IS_NOT_GIVEN = 4;
    const CODE_PRIORITY_IS_INVALID = 5;
    const CODE_UNKNOWN_ERROR = 999;


    public function run(Request $request, Response $response, array $args)
    {
        $id = $args['id'];

        /* @var Task $task */
        $task = null;
        try {
            $task = Task::query()->findOrFail($id);
        } catch (\Exception $e) {
            return $response
                ->withStatus(404)
                ->withJson([
                    'code'    => Update::CODE_TASK_DOES_NOT_EXIST,
                    'message' => sprintf('task with `id` `%d` does not exist', $id)
                ]);
        }

        // check if cardId is set
        if ($request->getParsedBodyParam('cardId', false) === false)
        {
            return $response
                ->withStatus(400)
                ->withJson([
                    'code'    => Update::CODE_CARD_ID_IS_NOT_GIVEN,
                    'message' => 'param `cardId` is required'
                ]);
        }

        // check if cardId is exist
        if (Card::query()->find($request->getParsedBodyParam('cardId')) === null)
        {
            return $response
                ->withStatus(400)
                ->withJson([
                    'code'    => Update::CODE_CARD_DOES_NOT_EXIST,
                    'message' => sprintf('card with id `%d` does not exist', $request->getParsedBodyParam('cardId'))
                ]);
        }

        // check if `name` is set
        if ($request->getParsedBodyParam('name', false) === false)
        {
            return $response
                ->withStatus(400)
                ->withJson([
                    'code'    => Update::CODE_NAME_IS_NOT_GIVEN,
                    'message' => 'param `name` is required'
                ]);
        }

        // check if `priority` value
        if (!in_array($request->getParsedBodyParam('priority', 'normal'), ['normal', 'high', 'low']))
        {
            return $response
                ->withStatus(400)
                ->withJson([
                    'code'    => Update::CODE_PRIORITY_IS_INVALID,
                    'message' => 'param `priority` must be `normal`, `high` or `low`'
                ]);
        }

        // save task
        try
        {
            $task->name     = $request->getParsedBodyParam('name');
            $task->marked   = $request->getParsedBodyParam('marked');
            $task->priority = $request->getParsedBodyParam('priority', Task::PRIORITY_NORMAL);
            $task->cardId   = $request->getParsedBodyParam('cardId');
            $task->saveOrFail();

            return $response->withStatus(204);
        } catch (\Exception $e) {
            return $response
                ->withStatus(500)
                ->withJson([
                    'code'    => Update::CODE_UNKNOWN_ERROR,
                    'message' => $e->getMessage()
                ]);
        }
    }

}

