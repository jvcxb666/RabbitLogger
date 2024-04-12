<?

namespace App\Connection;

use App\Interface\RabbitConnectable;
use App\Utils\ConfigProvider;
use Bunny\Channel;
use Bunny\Client;

class BaseConnection implements RabbitConnectable
{
    private Channel $channel;

    public function __construct()
    {
        $this->connect();
    }

    public function connect(): void
    {
        $client = new Client(ConfigProvider::getConfigVariable("rabbitConnection"));
        $client->connect();
        $this->channel = $client->channel();
    }

    public function getChannel(): Channel
    {
        return $this->channel;
    }
}