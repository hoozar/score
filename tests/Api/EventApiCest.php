<?php
declare(strict_types=1);

namespace Tests\Api;

use Tests\Support\ApiTester;

class EventApiCest
{
    public function testFoulEvent(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/event', [
            'type' => 'foul',
            'player' => 'William Saliba',
            'team_id' => 'arsenal',
            'match_id' => 'm1',
            'minute' => 45,
            'second' => 34
        ]);

        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'status' => 'success',
            'message' => 'Event saved successfully'
        ]);
        $I->seeResponseJsonMatchesJsonPath('$.event.type', 'foul');
    }

    public function testFoulEventWithoutRequiredFields(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/event', [
            'type' => 'foul',
            'player' => 'William Saliba',
            'minute' => 45,
            'second' => 34
            // Missing team_id and match_id
        ]);
        $r = $I->grabResponse();

        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'error' => "Validation failed: Team ID is required.\nMatch ID is required."
        ]);
    }

    public function testInvalidJson(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/event', 'invalid json');

        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => 'Malformed input data.']);
    }

    public function testEventWithoutType(ApiTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/event', [
            'player' => 'John Doe',
            'minute' => 23,
            'second' => 34
        ]);

        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'error' => "Validation failed: Event type is required.\nTeam ID is required.\nMatch ID is required."
        ]);
    }
}
