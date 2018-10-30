Extractor CLI
=========

Command-line interface for [Extractor](github.com/linkorb/extractor)

## Usage

    extractor run path/to/my-extractor.yaml mysql://user:pass@localhost/my-db -i color=red

Use the `-i` (or `--input`) flag to pass inputs into the extractor. You pass this flag multiple times if your extractor file requires multiple inputs.

Instead of passing the database connection string on the command-line, you can also ommit it, and define a `PDO` environment variable instead.

For more information about Extractor, please refer to [the library's README.md](github.com/linkorb/extractor).

## License

MIT (see [LICENSE.md](LICENSE.md))

## Brought to you by the LinkORB Engineering team

<img src="http://www.linkorb.com/d/meta/tier1/images/linkorbengineering-logo.png" width="200px" /><br />
Check out our other projects at [linkorb.com/engineering](http://www.linkorb.com/engineering).

Btw, we're hiring!



