<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Post;

final readonly class CreatePost1
{
    /** @param  array{}  $args */
    public function __invoke(null $_, array $args)
    {
        // TODO implement the resolver
        validator(
            ['author_id' => $args['author_id']],
            ['author_id' => 'integer|exists:users,id']
        ) -> validate();
        return Post::create($args);
    }
}
