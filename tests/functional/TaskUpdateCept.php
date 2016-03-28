<?php

// @group cards update

/* @var \Codeception\Scenario $scenario */
$guy = new TestGuy($scenario);
$guy->wantTo('update a task');

// update valid task
$guy->sendPUT('/tasks/1', [
    'cardId'   => 1,
    'name'     => 'something',
    'marked'   => false,
    'priority' => 'normal'
]);
$guy->seeResponseCodeIs(204);

// check `marked`: true
$guy->sendPUT('/tasks/1', [
    'cardId'   => 1,
    'name'     => 'something',
    'marked'   => true,
    'priority' => 'normal'
]);
$guy->seeResponseCodeIs(204);

// check without `marked`
$guy->sendPUT('/tasks/1', [
    'cardId'   => 1,
    'name'     => 'something',
    'priority' => 'normal'
]);
$guy->seeResponseCodeIs(204);
// check `priority`: "low"
$guy->sendPUT('/tasks/1', [
    'cardId'   => 1,
    'name'     => 'something',
    'marked'   => false,
    'priority' => 'low'
]);
$guy->seeResponseCodeIs(204);

// check `priority`: "high"
$guy->sendPUT('/tasks/1', [
    'cardId'   => 1,
    'name'     => 'something',
    'marked'   => false,
    'priority' => 'high'
]);
$guy->seeResponseCodeIs(204);

// check without `priority`
$guy->sendPUT('/tasks/1', [
    'cardId'   => 1,
    'name'     => 'something',
    'marked'   => false,
]);
$guy->seeResponseCodeIs(204);

// non existing task
$guy->sendPUT('/tasks/9', [
    'cardId'   => 1,
    'name'     => 'something',
    'marked'   => false,
    'priority' => 'normal'
]);
$guy->seeResponseIsJson();
$guy->seeResponseCodeIs(404);
$guy->seeResponseContains('"message":');

// no `cardId`
$guy->sendPUT('/tasks/1', [
    'name'     => 'something',
    'marked'   => false,
    'priority' => 'normal'
]);
$guy->seeResponseIsJson();
$guy->seeResponseCodeIs(400);
$guy->seeResponseContains('"message":');

// invalid cardId
$guy->sendPUT('/tasks/1', [
    'cardId'   => 999,
    'name'     => 'something',
    'marked'   => false,
    'priority' => 'normal'
]);
$guy->seeResponseIsJson();
$guy->seeResponseCodeIs(400);
$guy->seeResponseContains('"message":');

// no name
$guy->sendPUT('/tasks/1', [
    'cardId'   => 1,
    'marked'   => false,
    'priority' => 'normal'
]);
$guy->seeResponseIsJson();
$guy->seeResponseCodeIs(400);
$guy->seeResponseContains('"message":');

// invalid `priority`
$guy->sendPUT('/tasks/1', [
    'cardId'   => 1,
    'name'     => 'something',
    'marked'   => false,
    'priority' => 'bla'
]);
$guy->seeResponseIsJson();
$guy->seeResponseCodeIs(400);
$guy->seeResponseContains('"message":');