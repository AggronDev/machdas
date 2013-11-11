<?php


namespace Dingbat\Action\Task;

use Dingbat\Action;
use Dingbat\Model\Task;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class Index
 *
 * @category Action
 * @package  Dingbat\Action\Task
 * @author   Pierre Klink <dev@klinks.info>
 * @license  MIT http://opensource.org/licenses/MIT
 * @link     https://github.com/pklink/Dingbat
 */
class GetAll extends Action
{

    /**
     * Get all task
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function run()
    {
        $tasks = [];
        foreach (Task::objects()->orderBy('id', 'asc')->fetch() as $task) {
            $tasks[] = [
                'id'       => (int) $task->id,
                'name'     => $task->name,
                'marked'   => (bool) $task->marked,
                'priority' => $task->priority,
                'cardId'   => (int) $task->cardid
            ];
        }

        return JsonResponse::create($tasks);
    }

}

