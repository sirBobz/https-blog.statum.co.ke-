<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // Cleanup old posts
        Post::query()->forceDelete();
        // We keep Categories and Authors as requested, but ensure we have our targets.

        // Ensure Categories
        $categories = [
            'Fintech' => ['color' => '#3b82f6', 'description' => 'Financial technology innovations and trends.'],
            'Engineering' => ['color' => '#ef4444', 'description' => 'Technical deep dives and architectural patterns.'],
            'infrastructure' => ['color' => '#10b981', 'description' => 'Cloud, DevOps, and reliability engineering.'],
            'Security' => ['color' => '#8b5cf6', 'description' => 'Cybersecurity best practices for finance.'],
        ];

        foreach ($categories as $name => $meta) {
            Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'color' => $meta['color'],
                    'description' => $meta['description']
                ]
            );
        }

        // Ensure Tags
        $tags = ['M-Pesa', 'API', 'Laravel', 'Payments', 'Scalability', 'DevOps', 'Security', 'Kafka', 'Redis', 'AWS'];
        foreach ($tags as $tagName) {
            Tag::firstOrCreate(['slug' => Str::slug($tagName)], ['name' => $tagName]);
        }

        // Ensure Author
        $author = Author::firstOrCreate(
            ['slug' => 'engineering-team'],
            [
                'name' => 'Statum Engineering',
                'bio' => 'The team building the next generation of African fintech infrastructure.',
                'avatar' => 'authors/statum-avatar.jpg', // Placeholder, handled by UI fallback if missing
                'twitter' => '@statum_dev',
                'linkedin' => 'https://linkedin.com/company/statum'
            ]
        );

        $posts = [
            [
                'title' => 'Building Resilient Payment Gateways with M-Pesa',
                'category' => 'Fintech',
                'tags' => ['M-Pesa', 'Payments', 'API'],
                'excerpt' => 'Handling millions of transactions requires robust error handling and idempotency. Here is how we design for failure.',
                'content' => "
# Building Resilient Payment Gateways

When integrating with mobile money providers like M-Pesa, reliability is paramount. Network timeouts, callback failures, and third-party downtime are not edge cases—they are expected states.

## Idempotency is Key
Every transaction request must carry a unique reference ID. This allows us to safely retry requests without double-charging customers.

```php
public function charge(Request \$request)
{
    \$key = 'tx_' . \$request->input('reference');
    
    return Cache::lock(\$key, 10)->get(function () use (\$request) {
        // Process payment...
    });
}
```

## Handling Callhooks
Always validate the signature of incoming webhooks to ensure they originate from Safaricom.
                "
            ],
            [
                'title' => 'Optimizing API Latency for Real-Time Transactions',
                'category' => 'Engineering',
                'tags' => ['API', 'Scalability', 'Redis'],
                'excerpt' => 'Milliseconds matter in finance. We explore caching strategies and database tuning to achieve sub-100ms response times.',
                'content' => "
# Chasing Milliseconds

In high-frequency trading or real-time payments, every millisecond of latency correlates directly to user drop-off.

## Database Tuning
We moved from standard indexing to using partial indexes for active transactions.

```sql
CREATE INDEX idx_active_transactions ON transactions (status) WHERE status = 'pending';
```

## Caching Strategy
We utilize Redis not just for session storage, but for hot-data caching of account balances.
                "
            ],
            [
                'title' => 'Zero Trust Security for Financial Data',
                'category' => 'Security',
                'tags' => ['Security', 'DevOps'],
                'excerpt' => 'Perimeter security is no longer enough. Implementing Zero Trust architecture ensures our internal services are as secure as our public APIs.',
                'content' => "
# Beyond the Firewall

The traditional 'castle and moat' security model is obsolete. At Statum, we verify every request, regardless of whether it comes from the public internet or our internal VPC.

## Mutual TLS (mTLS)
Service-to-service communication is encrypted and authenticated using mTLS.

## Field-Level Encryption
Sensitive data like PII is encrypted at the application layer before it ever hits the database.
                "
            ],
            [
                'title' => 'Scaling Laravel for High Throughput',
                'category' => 'Engineering',
                'tags' => ['Laravel', 'Scalability', 'AWS'],
                'excerpt' => 'Laravel scales beautifully if you know how to tune it. Octane, Queues, and Horizontal Auto-scaling.',
                'content' => "
# Scaling Laravel

Many claim PHP can't scale. They are wrong.

## Laravel Octane
By keeping the application in memory, we reduced boot time overhead to zero.

## Horizontal Scaling on AWS
We use ECS Fargate to auto-scale our worker nodes based on queue depth.
                "
            ],
            [
                'title' => 'The State of African Payments: 2026 Outlook',
                'category' => 'Fintech',
                'tags' => ['Payments', 'M-Pesa'],
                'excerpt' => 'Cross-border settlements and interoperability are the next frontier. A look at the landscape.',
                'content' => "
# 2026 Outlook

Fragmentation is decreasing. With the Pan-African Payment and Settlement System (PAPSS), instant cross-border payments are becoming a reality.
                "
            ],
            [
                'title' => 'Event-Driven Architecture in Modern Banking',
                'category' => 'Infrastructure',
                'tags' => ['Kafka', 'Patterns'],
                'excerpt' => 'Decoupling services with Kafka allows us to process transaction ledgers asynchronously and reliably.',
                'content' => "
# Events First

Instead of direct HTTP calls between services, we publish events.

`TransactionCompleted` -> `LedgerService`, `NotificationService`, `AnalyticsService`.

This ensures that if the Notification service is down, the transaction is still recorded, and the user is notified later.
                "
            ],
            [
                'title' => 'Handling Webhooks at Scale',
                'category' => 'Engineering',
                'tags' => ['API', 'DevOps'],
                'excerpt' => 'Don\'t process webhooks synchronously. Accept, Queue, and Process.',
                'content' => "
# Webhook Best Practices

Never process business logic in the webhook controller.

1. Validate Signature.
2. Dispatch Job.
3. Return 200 OK immediately.
                "
            ],
            [
                'title' => 'Database Migrations without Downtime',
                'category' => 'Infrastructure',
                'tags' => ['DevOps', 'SQL'],
                'excerpt' => 'How to alter tables with millions of rows without locking the database.',
                'content' => "
# Zero Downtime Migrations

Always use `pt-online-schema-change` or Laravel's safe migration patterns.

Never rename a column. Create new, sync, then drop old.
                "
            ],
            [
                'title' => 'API Gateway Patterns with Kong',
                'category' => 'Infrastructure',
                'tags' => ['API', 'Security'],
                'excerpt' => 'Centralizing authentication, rate limiting, and logging at the edge.',
                'content' => "
# The Gateway Pattern

We use Kong to offload cross-cutting concerns from our microservices.
                "
            ],
            [
                'title' => 'Automating Compliance with CI/CD',
                'category' => 'Security',
                'tags' => ['DevOps', 'Security'],
                'excerpt' => 'Shift left. Security scans and compliance checks in the pipeline.',
                'content' => "
# Compliance as Code

We run `trivy` and `sonarqube` on every PR.
                "
            ],
            [
                'title' => 'Serverless Functions for Fintech',
                'category' => 'Engineering',
                'tags' => ['AWS', 'Scalability'],
                'excerpt' => 'When to use Lambda vs Containers for financial workloads.',
                'content' => "
# Serverless utility

Perfect for sporadic workloads like end-of-day reconciliation reports.
                "
            ],
            [
                'title' => 'Reliable SMS Delivery Systems',
                'category' => 'Fintech',
                'tags' => ['Notifications', 'API'],
                'excerpt' => 'Dealing with aggregator failures and delivery reports.',
                'content' => "
# SMS Reliability

SMS is unreliable by nature. We implemented a multi-provider fallback strategy.
                "
            ],
        ];

        foreach ($posts as $data) {
            $cat = Category::where('name', $data['category'])->first();

            $post = Post::create([
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'excerpt' => $data['excerpt'],
                'body' => $data['content'],
                'category_id' => $cat->id,
                'author_id' => $author->id,
                'published_at' => now()->subDays(rand(0, 30)),
                'status' => 'published',
                'featured_image' => null, // We'll let UI handle fallbacks
            ]);

            // Attach Tags
            $postTags = Tag::whereIn('name', $data['tags'])->get();
            $post->tags()->attach($postTags);
        }
    }
}
