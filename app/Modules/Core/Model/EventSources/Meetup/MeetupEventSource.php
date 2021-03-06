<?php declare(strict_types=1);

namespace App\Modules\Core\Model\EventSources\Meetup;

use App\Modules\Core\Model\Entity\Event;
use App\Modules\Core\Model\EventSources\EventSource;
use DMS\Service\Meetup\MeetupKeyAuthClient;
use Nette\Utils\DateTime;


class MeetupEventSource extends EventSource
{
	const URLS = [
		'meetup.com',
		'www.meetup.com',
	];

	private $apiKey;


	public function setApiKey(string $apiKey)
	{
		$this->apiKey = $apiKey;
	}


	/**
	 * Get upcoming events of the page
	 * @return Event[]
	 */
	public function getEvents(string $group): array
	{
		$client = MeetupKeyAuthClient::factory(array('key' => $this->apiKey));
		$groupEvents = $client->getGroupEvents(['urlname' => $group])->getData();

		$groupData = $client->getGroup(['urlname' => $group])->getData();
		$groupPhoto = isset($groupData['group_photo']) ? $groupData['group_photo']['photo_link'] : null;

		$events = [];
		foreach ($groupEvents as $event) {
			if ($event['status'] === 'upcoming') {
				$e = new Event;
				$e->setName($event['name']);
				$e->setDescription($event['description'] ?? '');
				$e->setStart(DateTime::from($event['time'] / 1000));
				$e->setOriginUrl($event['link']);
				if ($groupPhoto) {
					$e->setImage($groupPhoto);
				}
				$e->setRateByAttendeesCount($event['yes_rsvp_count'] + $event['waitlist_count']);
				$events[] = $e;
			}
		}
		return $events;
	}
}
