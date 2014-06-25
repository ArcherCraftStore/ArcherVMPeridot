# -*- encoding: utf-8 -*-

Gem::Specification.new do |s|
  s.name = "geminabox"
  s.version = "0.12.4"

  s.required_rubygems_version = Gem::Requirement.new(">= 0") if s.respond_to? :required_rubygems_version=
  s.authors = ["Tom Lea", "Jack Foy", "Rob Nichols"]
  s.date = "2014-03-19"
  s.description = "A sinatra based gem hosting app, with client side gem push style functionality."
  s.email = ["contrib@tomlea.co.uk", "jack@foys.net", "rob@undervale.co.uk"]
  s.extra_rdoc_files = ["README.markdown"]
  s.files = ["README.markdown"]
  s.homepage = "http://tomlea.co.uk/p/gem-in-a-box"
  s.licenses = ["MIT-LICENSE"]
  s.rdoc_options = ["--main", "README.markdown"]
  s.require_paths = ["lib"]
  s.rubygems_version = "2.0.2"
  s.summary = "Really simple rubygem hosting"

  if s.respond_to? :specification_version then
    s.specification_version = 4

    if Gem::Version.new(Gem::VERSION) >= Gem::Version.new('1.2.0') then
      s.add_runtime_dependency(%q<sinatra>, [">= 1.2.7"])
      s.add_runtime_dependency(%q<builder>, [">= 0"])
      s.add_runtime_dependency(%q<httpclient>, [">= 2.2.7"])
      s.add_runtime_dependency(%q<nesty>, [">= 0"])
      s.add_runtime_dependency(%q<faraday>, [">= 0"])
      s.add_development_dependency(%q<rake>, [">= 0"])
      s.add_development_dependency(%q<pry>, [">= 0"])
    else
      s.add_dependency(%q<sinatra>, [">= 1.2.7"])
      s.add_dependency(%q<builder>, [">= 0"])
      s.add_dependency(%q<httpclient>, [">= 2.2.7"])
      s.add_dependency(%q<nesty>, [">= 0"])
      s.add_dependency(%q<faraday>, [">= 0"])
      s.add_dependency(%q<rake>, [">= 0"])
      s.add_dependency(%q<pry>, [">= 0"])
    end
  else
    s.add_dependency(%q<sinatra>, [">= 1.2.7"])
    s.add_dependency(%q<builder>, [">= 0"])
    s.add_dependency(%q<httpclient>, [">= 2.2.7"])
    s.add_dependency(%q<nesty>, [">= 0"])
    s.add_dependency(%q<faraday>, [">= 0"])
    s.add_dependency(%q<rake>, [">= 0"])
    s.add_dependency(%q<pry>, [">= 0"])
  end
end
