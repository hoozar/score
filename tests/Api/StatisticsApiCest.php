<?php
declare(strict_types=1);

namespace Tests\Api;

use Tests\Support\ApiTester;

class StatisticsApiCest
{
    public function testGetTeamStatistics(ApiTester $I): void
    {
        $salt = (string)time();
        // First, create some foul events
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/event', [
            'type' => 'foul',
            'player' => 'William Saliba',
            'team_id' => 'arsenal',
            'match_id' => 'm1' . $salt,
            'minute' => 15,
            'second' => 34
        ]);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/event', [
            'type' => 'foul',
            'player' => 'Gabriel Jesus',
            'team_id' => 'arsenal',
            'match_id' => 'm1' . $salt,
            'minute' => 30,
            'second' => 33
        ]);

        // Now get team statistics
        $I->sendGET('/statistics?match_id=m1' . $salt . '&team_id=arsenal');

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'match_id' => 'm1' . $salt,
            'team_id' => 'arsenal',
            'statistics' => [
                'fouls' => 2
            ]
        ]);
    }

    public function testGetMatchStatistics(ApiTester $I): void
    {
        $salt = (string)time();
        // Create foul events for different teams
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/event', [
            'type' => 'foul',
            'player' => 'William Saliba',
            'team_id' => 'arsenal',
            'match_id' => 'm1' . $salt,
            'minute' => 15,
            'second' => 34
        ]);

        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/event', [
            'type' => 'foul',
            'player' => 'Virgil van Dijk',
            'team_id' => 'liverpool',
            'match_id' => 'm1' . $salt,
            'minute' => 30,
            'second' => 33
        ]);

        // Get all match statistics
        $I->sendGET('/statistics?match_id=m1' . $salt);

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'match_id' => 'm1' . $salt,
            'statistics' => [
                'arsenal' => [
                    'fouls' => 1
                ],
                'liverpool' => [
                    'fouls' => 1
                ]
            ]
        ]);
    }

    public function testGetStatisticsWithoutMatchId(ApiTester $I): void
    {
        $I->sendGET('/statistics');

        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'error' => 'match_id is required'
        ]);
    }

    public function testGetStatisticsForNonExistentTeam(ApiTester $I): void
    {
        $I->sendGET('/statistics?match_id=m1&team_id=nonexistent');

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'match_id' => 'm1',
            'team_id' => 'nonexistent',
            'statistics' => []
        ]);
    }

    public function testGetStatisticsForNonExistentMatch(ApiTester $I): void
    {
        $I->sendGET('/statistics?match_id=nonexistent');

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson([
            'match_id' => 'nonexistent',
            'statistics' => []
        ]);
    }
}
