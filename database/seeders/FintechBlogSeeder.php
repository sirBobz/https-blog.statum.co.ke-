<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class FintechBlogSeeder extends Seeder
{
    public function run(): void
    {
        // Cleanup existing posts but keep schema
        Post::query()->forceDelete();
        Category::query()->delete();
        Tag::query()->delete();
        Author::query()->delete();

        // Create Authors
        $authors = [
            [
                'name' => 'Silas Tatat',
                'slug' => 'silas-tatat',
                'bio' => 'Principal Engineer at Statum. Focused on building highly available payment gateways and developer-friendly APIs across Africa.',
                'twitter' => '@silastatat',
                'linkedin' => 'https://linkedin.com/in/silastatat',
            ],
            [
                'name' => 'Alice Kemunto',
                'slug' => 'alice-kemunto',
                'bio' => 'Distributed Systems Engineer. Specialist in SMS reliability and carrier-grade infrastructure.',
                'twitter' => '@alice_tech',
            ],
            [
                'name' => 'David Omondi',
                'slug' => 'david-omondi',
                'bio' => 'Security Researcher and Fintech Consultant. Helping startups secure their M-Pesa integrations.',
                'linkedin' => 'https://linkedin.com/in/davidomondi',
            ],
        ];

        foreach ($authors as $authorData) {
            Author::create($authorData);
        }

        $authorModels = Author::all();

        // Create Categories
        $categories = [
            ['name' => 'Engineering', 'color' => '#2563eb', 'description' => 'Deep dives into our technical infrastructure and architecture.'],
            ['name' => 'Fintech', 'color' => '#16a34a', 'description' => 'Insights into the African payments landscape and digital finance.'],
            ['name' => 'Security', 'color' => '#dc2626', 'description' => 'Securing financial data and maintaining trust at scale.'],
            ['name' => 'API Design', 'color' => '#9333ea', 'description' => 'Best practices for building developer-first APIs.'],
            ['name' => 'Infrastructure', 'color' => '#ea580c', 'description' => 'Scaling our systems to handle millions of transactions.'],
        ];

        foreach ($categories as $catData) {
            Category::create($catData);
        }

        $categoryModels = Category::all();

        // Create Tags
        $tags = ['M-Pesa', 'Laravel', 'API', 'Security', 'Scalability', 'Africa', 'Payments', 'SMS', 'Redis', 'Webhooks'];
        foreach ($tags as $tagName) {
            Tag::create(['name' => $tagName]);
        }
        $tagModels = Tag::all();

        // Posts Data
        $posts = [
            [
                'title' => 'Designing Resilient M-Pesa APIs at Scale',
                'excerpt' => 'How to handle timeouts, retries, and idempotency when integrating with mobile money gateways in high-traffic environments.',
                'body' => "Integrating with mobile money providers like M-Pesa requires a different mindset compared to standard REST APIs. Network volatility and asynchronous responses are the norm, not the exception.\n\n### Idempotency is Key\n\nWhen we send a STK Push request, we must ensure that a retry doesn't result in a double charge. We use a combination of `MerchantRequestID` and internal transaction IDs to map responses correctly.\n\n```php\npublic function initiatePayment(User \$user, int \$amount)\n{\n    \$idempotencyKey = 'pay_' . Str::random(32);\n    \n    return Cache::lock(\$idempotencyKey, 10)->get(function () use (\$amount) {\n        return Mpesa::stkPush([\n             'amount' => \$amount,\n             'reference' => 'Order #123'\n        ]);\n    });\n}\n```\n\n### Handling Timeouts\n\nMost M-Pesa timeouts occur at the gateway level. Instead of failing immediately, we push the transaction into a 'pending' state and poll via the Query API after 30 seconds if no callback is received.",
                'category_id' => $categoryModels->where('name', 'Engineering')->first()->id,
                'author_id' => $authorModels->where('name', 'Silas Tatat')->first()->id,
                'published_at' => now()->subDays(2),
                'meta_title' => 'Building Resilient M-Pesa Integrations | Statum Engineering',
            ],
            [
                'title' => 'SMS Delivery at Scale: What Fintech Developers Must Know',
                'excerpt' => 'Standard SMS gateways often fail during peak times. Here is how we ensure 99.9% delivery rates for OTPs and transaction alerts.',
                'body' => "SMS is still the primary notification channel for fintech in Africa. However, 'Fire and Forget' is not a strategy. \n\n### Multi-Route Fallbacks\n\nWe utilize multiple aggregators. If our primary route through Safaricom reports a delay in DLR (Delivery Receipts), we automatically pivot to our backup Airtel or international routes for critical OTPs.\n\n### Queuing Strategy\n\nWe use dedicated Redis queues for priority messages. \n\n```bash\n# Start a priority worker\nphp artisan queue:work --queue=high,default\n```",
                'category_id' => $categoryModels->where('name', 'Infrastructure')->first()->id,
                'author_id' => $authorModels->where('name', 'Alice Kemunto')->first()->id,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Airtime APIs: Handling Millions of Transactions Reliably',
                'excerpt' => 'A deep dive into the architecture behind our airtime disbursement engine and how we handle partial failures.',
                'body' => "Distributing airtime at scale involves interacting with legacy telco protocols. \n\n### The Challenge of Latency\n\nSome telcos take up to 45 seconds to confirm a payload. We use a 'Reconciler' pattern that periodically checks for final states of 'Processing' transactions.\n\n```json\n{\n  \"status\": \"reconciled\",\n  \"transactions\": 1250000,\n  \"failure_rate\": \"0.02%\"\n}\n```",
                'category_id' => $categoryModels->where('name', 'Fintech')->first()->id,
                'author_id' => $authorModels->where('name', 'Silas Tatat')->first()->id,
                'published_at' => now()->subDays(10),
            ],
            [
                'title' => 'Security Patterns for African Fintech Platforms',
                'excerpt' => 'Beyond SSL: Implementation of request signing, IP whitelisting, and data masking for multi-tenant payment systems.',
                'body' => "Security in fintech is about layers. \n\n### Request Signing\n\nEvery API call to our platform must include an `X-STATUM-SIGNATURE` header. This prevents MITM attacks and ensures payload integrity.\n\n```javascript\nconst signature = crypto\n  .createHmac('sha256', secret)\n  .update(JSON.stringify(payload))\n  .digest('hex');\n```",
                'category_id' => $categoryModels->where('name', 'Security')->first()->id,
                'author_id' => $authorModels->where('name', 'David Omondi')->first()->id,
                'published_at' => now()->subDays(15),
            ],
            [
                'title' => 'Building Developer-First Payment APIs',
                'excerpt' => 'How we designed our new SDKs and documentation to reduce integration time from days to minutes.',
                'body' => "A great API is invisible. We focused on standardizing error codes and providing 'Playground' environments that mirror production perfectly.\n\n### Error Code Consistency\n\nNever just return 400. Use specific error objects.\n\n```json\n{\n  \"error_code\": \"insufficient_balance\",\n  \"message\": \"Wallet does not have enough funds.\",\n  \"link\": \"https://docs.statum.co.ke/errors#insufficient_balance\"\n}\n```",
                'category_id' => $categoryModels->where('name', 'API Design')->first()->id,
                'author_id' => $authorModels->where('name', 'Silas Tatat')->first()->id,
                'published_at' => now()->subDays(20),
            ],
            [
                'title' => 'The Future of Open Banking in East Africa',
                'excerpt' => 'Exploring the regulatory shifts and technical hurdles of standardizing bank-to-wallet transfers.',
                'body' => "Open banking is coming to Kenya and Nigeria. We are at the forefront of building the middleware that will connect traditional banks to the modern fintech ecosystem.",
                'category_id' => $categoryModels->where('name', 'Fintech')->first()->id,
                'author_id' => $authorModels->where('name', 'Alice Kemunto')->first()->id,
                'published_at' => now()->subDays(25),
            ],
            [
                'title' => 'Scaling Real-time Lead Notifications with Redis',
                'excerpt' => 'How we use Redis Streams to handle thousands of concurrent webhooks without dropping a single event.',
                'body' => "When a customer pays via M-Pesa, the merchant needs to know immediately. We use Redis Streams to buffer these events and process them via multiple consumers.",
                'category_id' => $categoryModels->where('name', 'Infrastructure')->first()->id,
                'author_id' => $authorModels->where('name', 'David Omondi')->first()->id,
                'published_at' => now()->subDays(30),
            ],
            [
                'title' => 'Securing Webhooks: Best Practices for Fintechs',
                'excerpt' => 'Don\'t let fraudulent callbacks ruin your ledger. Learn how to verify webhook signatures securely.',
                'body' => "Webhooks are powerful but risky. Always verify the signature and ensure you use HTTPS. Also, implement idempotency on your receiving end.",
                'category_id' => $categoryModels->where('name', 'Security')->first()->id,
                'author_id' => $authorModels->where('name', 'David Omondi')->first()->id,
                'published_at' => now()->subDays(35),
            ],
            [
                'title' => 'Optimizing Mobile Money Checkouts for Conversion',
                'excerpt' => 'Reducing steps and friction in the payment flow can increase successful transactions by 30%.',
                'body' => "Every extra click is a chance for the customer to drop off. We analyzed thousands of transactions to find the optimal flow for STK push notifications.",
                'category_id' => $categoryModels->where('name', 'Fintech')->first()->id,
                'author_id' => $authorModels->where('name', 'Silas Tatat')->first()->id,
                'published_at' => now()->subDays(40),
            ],
            [
                'title' => 'Handling Partial Failures in Distributed Payment Systems',
                'excerpt' => 'What happens when half your transaction succeeds? Exploring the Saga pattern in fintech.',
                'body' => "Distributed transactions are hard. We use the Saga pattern to manage long-running transactions and ensure consistent state through compensation actions.",
                'category_id' => $categoryModels->where('name', 'Engineering')->first()->id,
                'author_id' => $authorModels->where('name', 'Alice Kemunto')->first()->id,
                'published_at' => now()->subDays(45),
            ],
        ];

        foreach ($posts as $postData) {
            $post = Post::create([
                'title' => $postData['title'],
                'slug' => Str::slug($postData['title']),
                'excerpt' => $postData['excerpt'],
                'body' => $postData['body'],
                'category_id' => $postData['category_id'],
                'author_id' => $postData['author_id'],
                'published_at' => $postData['published_at'],
                'status' => 'published',
                'meta_title' => $postData['meta_title'] ?? null,
                'meta_description' => $postData['excerpt'],
            ]);

            // Randomly assign 2-3 tags
            $post->tags()->attach(
                $tagModels->random(rand(2, 3))->pluck('id')->toArray()
            );
        }
    }
}
