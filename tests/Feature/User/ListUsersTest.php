<?php

it('returns 401 when listing users without a token', function () {
    $this->getJson('/api/users')->assertStatus(401);
});
