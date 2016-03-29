<?php


namespace Dingbat\Action\Task;

use Dingbat\Action;
use Dingbat\Model\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Respect\Validation\Exceptions\NestedValidationException;
use Slim\Http\Request;
use Slim\Http\Response;

class Update extends Action\AbstractImpl
{

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     * @throws ModelNotFoundException
     * @throws NestedValidationException
     */
    public function run(Request $request, Response $response, array $args)
    {
        // load and fill model
        /* @var Task $model */
        $model           = Task::query()->findOrFail($args['id']);
        $model->name     = $request->getParsedBodyParam('name');
        $model->marked   = (bool) $request->getParsedBodyParam('marked');
        $model->priority = $request->getParsedBodyParam('priority', Task::PRIORITY_NORMAL);
        $model->cardId   = (int) $request->getParsedBodyParam('cardId');

        // validation
        Task::validators()['name']->assert($model->name);
        Task::validators()['marked']->assert($model->marked);
        Task::validators()['priority']->assert($model->priority);
        Task::validators()['cardId']->assert($model->cardId);

        // save
        $model->saveOrFail();

        // response
        return $response->withStatus(204);
    }

}

