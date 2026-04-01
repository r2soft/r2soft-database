# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

R2Database is a PHP package providing a base Repository Pattern implementation on top of Laravel's Eloquent ORM (illuminate/database ^6.0). It includes a Criteria system for composable query filters.

## Commands

```bash
# Install dependencies
composer install

# Run all tests
vendor/bin/phpunit

# Run a single test file
vendor/bin/phpunit tests/R2Database/CategorytRepositoryTest.php

# Run a single test method
vendor/bin/phpunit --filter testMethodName
```

## Architecture

The package exposes one abstract class and three contracts:

- **`AbstractRepository`** — base repository that wraps an Eloquent model. Concrete repositories extend this and implement `model()` to return the FQCN of their Eloquent model. Provides CRUD (`all`, `create`, `update`, `delete`, `find`, `findBy`) and criteria management.
- **`RepositoryInterface`** — contract for CRUD operations.
- **`CriteriaInterface`** — contract with a single `apply($model, $repository)` method. Each criteria class adds query constraints (where clauses, ordering, etc.) to the Eloquent builder.
- **`CriteriaCollection`** — contract for managing criteria on a repository (`addCriteria`, `applyCriteria`, `ignoreCriteria`, `clearCriteria`).

Criteria are applied lazily: they accumulate via `addCriteria()` and are applied to the query builder when `all()`, `find()`, or `findBy()` is called. `clearCriteria()` resets both the collection and the model instance.

## Testing

Tests use Orchestra Testbench with an in-memory SQLite database. The base test class (`tests/AbstractTestCase.php`) handles migration loading and database configuration. Test stub models, repositories, criteria, and migrations live under `tests/resources/`.

## Namespace / Autoloading

PSR-4: `R2Soft\R2Database\` maps to `src/R2Database/`.
