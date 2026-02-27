# Prometheus client for laravel framework

[![Latest Version on Packagist](https://img.shields.io/packagist/v/vmorozov/laravel-prometheus.svg?style=flat-square)](https://packagist.org/packages/vmorozov/laravel-prometheus)
[![Tests](https://img.shields.io/github/actions/workflow/status/freezer278/laravel-prometheus/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/freezer278/laravel-prometheus/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/vmorozov/laravel-prometheus.svg?style=flat-square)](https://packagist.org/packages/vmorozov/laravel-prometheus)

A Laravel package for integrating Prometheus metrics collection into your Laravel application.  
This package makes it easy to collect and expose metrics for monitoring your application's performance and behavior.

## Installation

1. Install the package via composer:

```bash
composer require vmorozov/laravel-prometheus
```
2. Publish vendor files:
```bash
php artisan vendor:publish --provider="VMorozov\\Prometheus\\PrometheusServiceProvider"
```

## Upgrading version
1. Update the package version in `composer.json`
2. Run 
```bash
composer update
```
3. Run command to update configs: 
```bash
php artisan vendor:publish --provider="VMorozov\\Prometheus\\PrometheusServiceProvider" --force
```
4. Run clear stored metrics: 
```bash
php artisan prometheus:clear_stored_metrics
```

## Usage

This package provides default metrics that allow you to monitor response times (divided into buckets and percentiles) and request counts.   
It also allows you to create custom metrics and collectors for your specific needs.

To see the collected metrics, go to the `/metrics` endpoint of your application.   
This endpoint returns metrics in the Prometheus exposition format, which can be scraped by a Prometheus server.

You can clear stored metrics using the built-in `prometheus:clear_stored_metrics` command:
```bash
php artisan prometheus:clear_stored_metrics
`````

## Configuration

The package can be configured using the `laravel-prometheus.php` configuration file.  
This file is published to your application's `config` directory when you run the `vendor:publish` command.

Key configuration options include:

- **namespace**: Prefix for all metrics (default: your APP_NAME env value)
- **storage_type**: How metrics data is stored (**redis**, in_memory, apcu)
- **route_url**: URL path for the metrics endpoint (default: `/metrics`)
- **default_metrics_enabled**: Whether default metrics are enabled (they are enabled by default)
- **on_demand_metric_collectors**: Collectors that run only when the metrics endpoint is accessed

For detailed configuration information, see the [Configuration Documentation](docs/configuration.md).

## Default Metrics

The package includes two default metrics:

1. **Request Duration Histogram**: Measures the duration of HTTP requests.
2. **Queue Size Gauge**: Measures the size of Laravel queues (you have to configure which connections and queues to monitor).

For more information about the default metrics, see the [Default Metrics Documentation](docs/default-metrics.md).

## Custom Metrics

You can create custom metrics to monitor specific aspects of your application. The package supports three types of metrics:

1. **Counter**: A cumulative metric that only increases (requests_processed, users_registered, etc.)
2. **Gauge**: A metric that can go up and down and represents current state of something (example: queue_size, cpu_load, etc.)
3. **Histogram**: A metric that samples observations and counts them in buckets (when you need to have quantiles of your metric values)

For information on creating and using custom metrics, see the [Custom Metrics Documentation](docs/custom-metrics.md).

## On-Demand Metrics Collectors

On-Demand Metrics Collectors are classes that collect metrics only when the metrics endpoint is accessed.   
This is useful for metrics that are expensive to collect or that don't need to be updated on every request.

For information on creating and using custom On-Demand Metrics Collectors, see the [On-Demand Metrics Collectors Documentation](docs/on-demand-metrics-collectors.md).

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/spatie/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Vladimir Morozov](https://github.com/vmorozov)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
